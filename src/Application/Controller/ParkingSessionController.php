<?php

declare(strict_types=1);

namespace App\Application\Controller;

use App\Domain\ParkingSession;
use App\Domain\ParkingSessionRepository;
use App\Domain\ParkingSessionValidator;
use App\Domain\Services\TariffCalculator;

/**
 * ControladorDeSessãoDeEstacionamento
 *
 * Gerencia todas as operações de sessão de estacionamento, incluindo:
 * - Criação de novas sessões de estacionamento
 * - Listagem de todas as sessões
 * - Edição de sessões existentes
 * - Exclusão de sessões
 *
 * @package App\Application\Controller
 */
final class ParkingSessionController extends BaseController
{
    private ParkingSessionRepository $repository;
    private ParkingSessionValidator $validator;
    private TariffCalculator $calculator;

    /**
     * Construtor
     *
     * @param ParkingSessionRepository $repository Repositório para persistência
     * @param ParkingSessionValidator $validator Validador para regras de negócio
     * @param TariffCalculator $calculator Calculadora de tarifas de estacionamento
     */
    public function __construct(
        ParkingSessionRepository $repository,
        ParkingSessionValidator $validator,
        TariffCalculator $calculator
    ) {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->calculator = $calculator;
    }

    /**
     * Exibe o formulário de criação de sessão
     *
     * @return void
     */
    public function showCreateForm(): void
    {
        $errors = $_SESSION['errors'] ?? [];
        $oldInput = $_SESSION['oldInput'] ?? [];
        unset($_SESSION['errors'], $_SESSION['oldInput']);
        
        $this->render('parking_session/create', ['errors' => $errors, 'oldInput' => $oldInput], 'Criar Sessão');
    }

    /**
     * Cria uma nova sessão de estacionamento
     *
     * @return void
     */
    public function create(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/session/create');
            return;
        }

        $input = [
            'plate' => $this->post('plate'),
            'vehicleType' => $this->post('vehicleType'),
            'parkedHours' => $this->post('parkedHours'),
            'entryTime' => $this->post('entryTime'),
        ];

        $errors = $this->validator->validate($input);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['oldInput'] = $input;
            $this->redirect('/session/create');
            return;
        }

        $plate = strtoupper(trim((string)$input['plate']));
        $vehicleType = strtolower(trim((string)$input['vehicleType']));
        $parkedHours = (int)$input['parkedHours'];
        $entryTime = new \DateTime($input['entryTime']);
        $finalTariff = $this->calculator->calculate($vehicleType, $parkedHours);

        $session = new ParkingSession(
            $plate,
            $vehicleType,
            $parkedHours,
            $finalTariff,
            $entryTime
        );

        $this->repository->add($session);

        $_SESSION['success'] = 'Sessão de estacionamento criada com sucesso! ID: ' . $session->getId();
        $this->redirect('/session/list');
    }

    /**
     * Exibe a lista de todas as sessões de estacionamento
     *
     * @return void
     */
    public function listAll(): void
    {
        $sessions = $this->repository->getAll();
        $this->render('parking_session/list', ['sessions' => $sessions], 'Lista de Sessões');
    }

    /**
     * Exibe o formulário de edição de sessão
     *
     * @return void
     */
    public function showEditForm(): void
    {
        // debug: registrar chamada ao método para diagnóstico
        @file_put_contents(__DIR__ . '/../../../storage/debug_render.log', "showEditForm called\n", FILE_APPEND);

        $id = (int)$this->get('id');

        if ($id <= 0) {
            $this->redirect('/session/list');
            return;
        }

        $session = $this->repository->getById($id);

        if (!$session) {
            $_SESSION['error'] = 'Sessão de estacionamento não encontrada';
            @file_put_contents(__DIR__ . '/../../../storage/debug_render.log', "sessão não encontrada id={$id}\n", FILE_APPEND);
            $this->redirect('/session/list');
            return;
        }

        $errors = $_SESSION['errors'] ?? [];
        $oldInput = $_SESSION['oldInput'] ?? [];
        unset($_SESSION['errors'], $_SESSION['oldInput']);
        
        @file_put_contents(__DIR__ . '/../../../storage/debug_render.log', "about to render view for id={$id}\n", FILE_APPEND);
        $this->render('parking_session/edit', ['session' => $session, 'errors' => $errors, 'oldInput' => $oldInput], 'Editar Sessão');
    }

    /**
     * Atualiza uma sessão de estacionamento
     *
     * @return void
     */
    public function update(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/session/list');
            return;
        }

        $id = (int)$this->post('id');
        $session = $this->repository->getById($id);

        if (!$session) {
            $_SESSION['error'] = 'Parking session not found';
            $this->redirect('/session/list');
            return;
        }

        $input = [
            'plate' => $this->post('plate'),
            'vehicleType' => $this->post('vehicleType'),
            'parkedHours' => $this->post('parkedHours'),
            'entryTime' => $this->post('entryTime'),
        ];

        $errors = $this->validator->validate($input);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['oldInput'] = $input;
            $this->redirect('/session/edit?id=' . $id);
            return;
        }

        $plate = strtoupper(trim((string)$input['plate']));
        $vehicleType = strtolower(trim((string)$input['vehicleType']));
        $parkedHours = (int)$input['parkedHours'];
        $entryTime = new \DateTime($input['entryTime']);
        $finalTariff = $this->calculator->calculate($vehicleType, $parkedHours);

        $updatedSession = new ParkingSession(
            $plate,
            $vehicleType,
            $parkedHours,
            $finalTariff,
            $entryTime
        );
        $updatedSession->setId($id);

        $this->repository->update($updatedSession);

        $_SESSION['success'] = 'Sessão de estacionamento atualizada com sucesso!';
        $this->redirect('/session/list');
    }

    /**
     * Exclui uma sessão de estacionamento
     *
     * @return void
     */
    public function delete(): void
    {
        $id = (int)$this->get('id');

        if ($id <= 0) {
            $this->redirect('/session/list');
            return;
        }

        try {
            $this->repository->delete($id);
            $_SESSION['success'] = 'Sessão de estacionamento excluída com sucesso!';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Falha ao excluir sessão de estacionamento: ' . $e->getMessage();
        }

        $this->redirect('/session/list');
    }
}
