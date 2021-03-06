<?php namespace SportExperiment\Router;

use Illuminate\Support\Facades\Request;
use SportExperiment\Controller\Researcher\Login;
use SportExperiment\Controller\Subject\PreGame\Registration;
use SportExperiment\Controller\Subject\PreGameHold;
use SportExperiment\Controller\Subject\Experiment;
use SportExperiment\Model\Eloquent\Subject;
use SportExperiment\Model\Eloquent\SessionState;
use SportExperiment\Model\Eloquent\SubjectState;
use SportExperiment\Controller\Subject\Payoff;
use SportExperiment\Controller\Subject\Questionnaire;
use SportExperiment\Controller\Subject\Completed;

use SportExperiment\Controller\Subject\PreGame\Questionnaire as PreGameQuestionnaire;

class SubjectRouter
{
    private $route;

    public function __construct()
    {
        $this->route = [
            SubjectState::$REGISTRATION=>Registration::getRoute(),
            SubjectState::$PRE_GAME_QUESTIONNAIRE_STATE=>PreGameQuestionnaire::getRoute(),
            SubjectState::$PRE_GAME_HOLD_STATE=>PreGameHold::getRoute(),
            SubjectState::$GAME_PLAY=>Experiment::getRoute(),
            SubjectState::$COMPLETED=>Login::getRoute(),
            SubjectState::$PAYOFF=>Payoff::getRoute(),
            SubjectState::$OUTGOING_QUESTIONNAIRE=>Questionnaire::getRoute(),
            SubjectState::$COMPLETED=>Completed::getRoute()
        ];
    }

    public function getGameStateRoute($state)
    {
        return $this->route[$state->getState()];
    }

    public function isCurrentRouteValid(Subject $subject)
    {
        $currentRoute = Request::path();
        $subjectStateRoute = $this->getRoute($subject);
        return $subjectStateRoute == $currentRoute;
    }

    public function getRoute(Subject $subject)
    {
        $sessionState = $subject->session->getState();
        $subjectGameState = $subject->getState();

        // Todo: move game play state change to Session->updateSession()

        /*
         * The subject's game state is auto transitioned in two cases:
         */
        if (SubjectState::isPreGameHoldState($subjectGameState) && SessionState::isStartedState($sessionState)) {
            $subject->setState(SubjectState::$GAME_PLAY);
            $subject->save();
            return $this->route[SubjectState::$GAME_PLAY];
        }

        if (SubjectState::isGamePlayState($subjectGameState) && SessionState::isStoppedState($sessionState)) {
            $subject->setState(SubjectState::$PAYOFF);
            $subject->save();
            return $this->route[SubjectState::$PAYOFF];
        }


        return $this->route[$subjectGameState];
    }
}