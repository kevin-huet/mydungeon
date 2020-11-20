<?php


namespace App\Controller\WoW\DungeonGroup;

use App\Entity\BlizzardUser;
use App\Entity\User;
use App\Entity\WoW\DungeonGroup;
use App\Form\DungeonGroupFormType;
use App\Form\DungeonGroupRequestType;
use App\Repository\WoW\DungeonGroupRepository;
use App\Repository\WoW\DungeonGroupRequestRepository;
use App\Service\Api\RaiderioApiService;
use App\Service\WoW\DungeonGroupService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class GroupController
 * @package App\Controller\WoW\DungeonGroup
 * @Route("/groups")
 * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN') or is_granted('ROLE_MOD')")
 *
 */
class GroupController extends AbstractController
{
    public const STATUS_WAIT = 0;
    public const STATUS_REFUSED = 1;
    public const STATUS_ACCEPTED = 2;

    /**
     * @var DungeonGroupRepository
     */
    private $groupRepository;
    /**
     * @var DungeonGroupService
     */
    private $groupService;
    /**
     * @var RaiderioApiService
     */
    private $raiderApi;
    /**
     * @var DungeonGroupRequestRepository
     */
    private $requestRepository;

    public function __construct(DungeonGroupRepository $dungeonGroupRepository, DungeonGroupService $groupService,
        RaiderioApiService $raiderioApiService, DungeonGroupRequestRepository $requestRepository)
    {
        $this->groupRepository = $dungeonGroupRepository;
        $this->groupService = $groupService;
        $this->raiderApi = $raiderioApiService;
        $this->requestRepository = $requestRepository;
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/create/", name="app_dungeon_group_create")
     */
    public function createGroup(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var BlizzardUser $blizzardUser */
        $blizzardUser = $user->getBlizzardUser();
        $form = $this->createForm(DungeonGroupFormType::class, null, ['user' => $blizzardUser->getId()]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->groupService->createDungeonGroup($data, $user);
        }
        return $this->render('wow/dungeon/dungeon_group_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/", name="app_dungeon_group")
     * @return Response
     */
    public function showGroups()
    {
        $groups =  $this->groupRepository->findAll();
        $blizzardUser = $this->getUser()->getBlizzardUser();
        $alreadySend = [];
        foreach ($groups as $group) {
            if (!$blizzardUser)
                break;
            foreach ($group->getRequests() as $request) {
                if ($request->getSender() == $blizzardUser && $request->getStatus() != self::STATUS_REFUSED) {
                    $alreadySend[] = $group->getId();
                    break;
                }
            }
        }
        return $this->render('wow/dungeon/show_groups.html.twig', [
            'groups' => $groups,
            'blizzardUser' => $blizzardUser,
            'alreadySend'=> $alreadySend
        ]);
    }

    /**
     * @Route("/show/{group}", name="app_dungeon_group_show")
     * @param DungeonGroup $group
     * @return Response
     */
    public function showGroup(DungeonGroup $group)
    {
        $memberRaiderIo = [];
        foreach ($group->getMembers() as $member) {
            if ($member->getCharacters())
                $memberRaiderIo[] = $this->raiderApi->getCharacter($member->getCharacters()->getName(), $member->getCharacters()->getRealm(), 'eu');
        }
        return $this->render('wow/dungeon/show_group_details.html.twig', [
            'group' => $group,
            'members' => $memberRaiderIo,
        ]);
    }

    /**
     * @Route("/delete/{group}", name="app_dungeon_group_delete")
     */
    public function deleteGroup(DungeonGroup $group)
    {
        $this->groupService->deleteGroup($group);
        return $this->redirectToRoute('app_dungeon_group');
    }

    /**
     * @Route("/request/{group}", name="app_dungeon_send_group_request")
     * @param Request $request
     * @param DungeonGroup $group
     * @return Response
     */
    public function sendGroupRequest(Request $request, DungeonGroup $group)
    {
        /** @var User $user */
        $user = $this->getUser();
        $blizzardUser = $user->getBlizzardUser();
        $form = $this->createForm(DungeonGroupRequestType::class, null, [
            'user' => $blizzardUser->getId(),
            'action' => $this->generateUrl('app_dungeon_send_group_request', [
                'group' => $group->getId()
            ]),
        ]);
        $memberRaiderIo = [];
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->groupService->createRequest($data, $blizzardUser, $group);
            return $this->redirectToRoute('app_dungeon_group');
        }
        foreach ($group->getMembers() as $member) {
            $memberRaiderIo[] = $this->raiderApi->getCharacter($member->getCharacters()->getName(), $member->getCharacters()->getRealm(), 'eu');
        }
        return $this->render('wow/dungeon/send_group_request.html.twig', [
            'blizzardUser' => $blizzardUser,
            'group' => $group,
            'members' => $memberRaiderIo,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/requests/", name="app_dungeon_show_requests")
     * @return Response
     */
    public function showRequests()
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var BlizzardUser $blizzardUser */
        $blizzardUser = $user->getBlizzardUser();
        $groups = $blizzardUser->getDungeonGroup();
        $requests = new ArrayCollection();
        foreach ($groups as $group) {
            $requests = new ArrayCollection(array_merge($requests->toArray(), $group->getRequests()->toArray()));
        }

        return $this->render('wow/dungeon/show_requests.html.twig', [
            'requests' => $requests,
        ]);
    }

    /**
     * @Route("/accept/request", name="app_accept_request")
     * @param Request $request
     * @return JsonResponse
     */
    public function acceptRequest(Request $request)
    {
        if($request->request->get('some_var_name') && $request->request->get('some_var_name') == "accepted"){
            $arrData = ['output' => $this->groupService->changeRequestGroupStatus($this->requestRepository->findOneById(
                $request->request->get('groupRequest')),
                self::STATUS_ACCEPTED, $request->request->get('role')),
                ];
            return new JsonResponse($arrData);
        } elseif ($request->request->get('some_var_name')) {
            $arrData = ['output' => $this->groupService->changeRequestGroupStatus($this->requestRepository->findOneById(
                $request->request->get('groupRequest')),
                self::STATUS_REFUSED, $request->request->get('role')),];
            return new JsonResponse($arrData);
        }
        return new JsonResponse();
    }
}