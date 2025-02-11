<?php

namespace OCA\CfgShareLinks\Controller;

use OCA\CfgShareLinks\AppInfo\Application;
use OCA\CfgShareLinks\Service\ShareService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\IRequest;
use OCP\Lock\LockedException;

class ShareController extends Controller {
	/** @var ShareService */
	private ShareService $service;

	/** @var string */
	private string $userId;

	use Errors;

	public function __construct(
		IRequest $request,
		ShareService $service,
		$userId
	) {
		parent::__construct(Application::APP_ID, $request);
		$this->service = $service;
		$this->userId = $userId;
	}

	/**
	 * @NoAdminRequired
	 */
	public function create(string $path, int $shareType, string $tokenCandidate, string $password = ""): DataResponse {
		//        return new DataResponse($this->service->create($path, $shareType, $tokenCandidate,
		//            $this->userId));
		return $this->handleException(function () use ($path, $shareType, $tokenCandidate, $password) {
			return $this->service->create($path, $shareType, $tokenCandidate, $this->userId, $password);
		});
	}

	/**
	 * @NoAdminRequired
	 */
	public function update(string $id, string $path, string $currentToken, string $tokenCandidate): DataResponse {
		return $this->handleException(function () use ($id, $path, $currentToken, $tokenCandidate) {
			return $this->service->update($id, $path, $currentToken, $tokenCandidate, $this->userId);
		});
	}

	/**
	 * @throws LockedException
	 */
	public function cleanup() {
		$this->service->cleanup();
	}
}
