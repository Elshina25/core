<?php

namespace Ita\Routes\Controllers;

use \Bitrix\Main\ {
    Context,
    Loader
};
use Bitrix\Main\Error;

class Forms extends \Bitrix\Main\Engine\Controller
{
	public function configureActions()
	{
		return [
			'callbackForm' => [
				'prefilters' => [],
				'postfilters' => []
			],
			'researchForm' => [
				'prefilters' => [],
				'postfilters' => []
			],
            'helpInSelectionForm' => [
                'prefilters' => [],
                'postfilters' => []
            ],
            'requestForViewForm' => [
                'prefilters' => [],
                'postfilters' => []
            ],
            'requestPriceForm' => [
                'prefilters' => [],
                'postfilters' => []
            ],
            'requestResearchForm' => [
                'prefilters' => [],
                'postfilters' => []
            ],
            'subscribeReportForm' => [
                'prefilters' => [],
                'postfilters' => []
            ],
            'subscribeReportSections' => [
                'prefilters' => [],
                'postfilters' => []
            ],
            'discussTaskLists' => [
                'prefilters' => [],
                'postfilters' => []
            ],
            'discussTaskForm' => [
                'prefilters' => [],
                'postfilters' => []
            ],
		];
	}

	public function callbackFormAction()
    {
        Loader::includeModule("form");
        $fields = [
            "NAME",
            "PHONE",
            "EMAIL",
            "QUESTIONS",
            "PAGE",
        ];
        $requiredFields = [
            "NAME",
            "PHONE",
            "EMAIL",
            "PAGE"
        ];
        $values = [];
        $errors = [];
        $request = Context::getCurrent()->getRequest();
        foreach ($fields as $field) {
            $values[$field] = $request->getPost(\mb_strtolower($field));
            if (in_array($field, $requiredFields) && empty($values[$field])) {
                $this->addError(new Error(
                    "Не заполнено поле " . \mb_strtolower($field) . "!",
                    401,
                    ["text" => "Не заполнено поле " . \mb_strtolower($field) . "!", "code" => \mb_strtolower($field)]
                ));
                $errors[] = $field;
            }
        }
        if(!filter_var($values["EMAIL"], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "EMAIL";
            $this->addError(new Error("Не валидный email!", 401, ["text" => "Не валидный email!", "code" => "email"]));
        }
        $check = $request->getPost("check");
        if ($check) {
            $this->addError(new Error("Не пройдена анти-спам проверка!", 401, ["text" => "Не пройдена анти-спам проверка!", "code" => "check"]));
        }
        if (count($errors) > 0) {
            return null;
        }
        $formSID = "SIMPLE_FORM_2";
        $rsForm = \CForm::GetBySID($formSID);
        $arForm = $rsForm->Fetch();
        $formId = $arForm["ID"];
        foreach ($fields as $field) {
            $is_filtered = false;
            $rsQuestions = \CFormField::GetList(
                $formId,
                "ALL",
                $by="s_id",
                $order="desc",
                ["SID" => $field],
                $is_filtered
            );
            if ($question = $rsQuestions->Fetch()) {
                $questionId = $question["ID"];
                $is_filtered = false;
                $filter = [];
                $rsAnswers = \CFormAnswer::GetList(
                    $questionId,
                    $by="s_id",
                    $order="desc",
                    $filter,
                    $is_filtered
                );
                if ($answer = $rsAnswers->Fetch()) {
                    $data["form_" . $answer["FIELD_TYPE"]. "_" . $answer["ID"]] = $values[$field];
                }
            }
        }
        $form = new \CFormResult();
        if ($RESULT_ID = $form->Add($formId, $data, "N")) {
            \CFormCRM::onResultAdded($formId, $RESULT_ID);
            \CFormResult::SetEvent($RESULT_ID);
            \CFormResult::Mail($RESULT_ID);
            return ["success" => true, "result" => $RESULT_ID];
        } else {
            global $strError;
            $this->addError(new Error("Ошибка отправки формы! ". $strError, 500));
            return null;
        }
    }

	public function researchFormAction()
	{
		$request = Context::getCurrent()->getRequest();
		$name = $request->getPost('name');
		$phone = $request->getPost('phone');
		$email = $request->getPost('email');
		$question = $request->getPost('questions');
		$company = $request->getPost('companyName');

		$errors = [];
		if(empty($name)) {
			$errors[] = ["text" => "Не введено имя!", "code" => "name"];
			$this->addError(new Error("Не введено имя!", 401, ["text" => "Не введено имя!", "code" => "name"]));
		}
		if(empty($phone)) {
			$errors[] = ["text" => "Не введен телефон!", "code" => "phone"];
			$this->addError(new Error("Не введен телефон!", 401, ["text" => "Не введен телефон!", "code" => "phone"]));
		}
		if(empty($email)) {
			$errors[] = ["text" => "Не введен email!", "code" => "email"];
			$this->addError(new Error("Не введен email!", 401, ["text" => "Не введен email!", "code" => "email"]));
		}
		if(empty($question)) {
			$errors[] = ["text" => "Не введено сообщение!", "code" => "questions"];
			$this->addError(new Error("Не введено сообщение!", 401, ["text" => "Не введено сообщение!", "code" => "questions"]));
		}
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors[] = ["text" => "Не валидный email!", "code" => "email"];
			$this->addError(new Error("Не валидный email!", 401, ["text" => "Не валидный email!", "code" => "email"]));
		}
        $check = $request->getPost("check");
        if ($check) {
            $this->addError(new Error("Не пройдена анти-спам проверка!", 401, ["text" => "Не пройдена анти-спам проверка!", "code" => "check"]));
        }
		if(count($errors) > 0) {
			return [];
		}
		$FORM_ID = 8;
		$arValues = [
			"form_text_80" => $name,
			"form_text_81" => $phone,
			"form_text_82" => $email,
			"form_textarea_83" => $question,
			"form_text_152" => $company
		];

		Loader::includeModule("form");
		$form = new \CFormResult();
		//если результат добавился в веб форму, передаем ID и поля
		if ($RESULT_ID = $form->Add($FORM_ID, $arValues, "N")) {
            \CFormCRM::onResultAdded($formId, $RESULT_ID);
            \CFormResult::SetEvent($RESULT_ID);
            \CFormResult::Mail($RESULT_ID);
			return ["success" => true, "result" => $RESULT_ID];
		} else {
			$this->addError(new Error("Ошибка отправки формы!", 500));
			return [];
		}
	}

	public function helpInSelectionFormAction()
    {
        Loader::includeModule("form");
        $fields = [
            "phone" => "PHONE",
            "page" => "PAGE",
            "crmId" => "CRM_ID",
        ];
        $requiredFields = [
            "PHONE",
            "PAGE",
        ];
        $values = [];
        $errors = [];
        $request = Context::getCurrent()->getRequest();
        foreach ($fields as $key => $field) {
            $values[$field] = $request->getPost($key);
            if (in_array($field, $requiredFields) && empty($values[$field])) {
                $this->addError(new Error(
                    "Не заполнено поле " . $key . "!",
                    401,
                    ["text" => "Не заполнено поле " . $key . "!", "code" => $key]
                ));
                $errors[] = $field;
            }
        }
        $check = $request->getPost("check");
        if ($check) {
            $this->addError(new Error("Не пройдена анти-спам проверка!", 401, ["text" => "Не пройдена анти-спам проверка!", "code" => "check"]));
        }
        if (count($errors) > 0) {
            return null;
        }
        $formSID = "GET_HELP_IN_SELECTION";
        $rsForm = \CForm::GetBySID($formSID);
        $arForm = $rsForm->Fetch();
        $formId = $arForm["ID"];
        foreach ($fields as $field) {
            $is_filtered = false;
            $rsQuestions = \CFormField::GetList(
                $formId,
                "ALL",
                $by="s_id",
                $order="desc",
                ["SID" => $field],
                $is_filtered
            );
            if ($question = $rsQuestions->Fetch()) {
                $questionId = $question["ID"];
                $is_filtered = false;
                $filter = [];
                $rsAnswers = \CFormAnswer::GetList(
                    $questionId,
                    $by="s_id",
                    $order="desc",
                    $filter,
                    $is_filtered
                );
                if ($answer = $rsAnswers->Fetch()) {
                    $data["form_" . $answer["FIELD_TYPE"]. "_" . $answer["ID"]] = $values[$field];
                }
            }
        }
        $form = new \CFormResult();
        if ($RESULT_ID = $form->Add($formId, $data, "N")) {
            \CFormCRM::onResultAdded($formId, $RESULT_ID);
            \CFormResult::SetEvent($RESULT_ID);
            \CFormResult::Mail($RESULT_ID);
            return ["success" => true, "result" => $RESULT_ID];
        } else {
            $this->addError(new Error("Ошибка отправки формы!", 500));
            return null;
        }
    }

    public function requestForViewFormAction()
    {
        Loader::includeModule("form");
        $fields = [
            "name" => "NAME",
            "phone" => "PHONE",
            "email" => "EMAIL",
            "square" => "SQUARE",
            "message" => "MESSAGE",
            "page" => "PAGE",
            "crmId" => "CRM_ID",
        ];
        $requiredFields = [
            "NAME",
            "PHONE",
            "EMAIL",
            "SQUARE",
            "PAGE",
            "CRM_ID",
        ];
        $values = [];
        $errors = [];
        $request = Context::getCurrent()->getRequest();
        foreach ($fields as $key => $field) {
            $values[$field] = $request->getPost($key);
            if (in_array($field, $requiredFields) && empty($values[$field])) {
                $this->addError(new Error(
                    "Не заполнено поле " . $key . "!",
                    401,
                    ["text" => "Не заполнено поле " . $key . "!", "code" => $key]
                ));
                $errors[] = $field;
            }
        }
        $check = $request->getPost("check");
        if ($check) {
            $this->addError(new Error("Не пройдена анти-спам проверка!", 401, ["text" => "Не пройдена анти-спам проверка!", "code" => "check"]));
        }
        if (count($errors) > 0) {
            return null;
        }
        $formSID = "REQUEST_FOR_VIEW";
        $rsForm = \CForm::GetBySID($formSID);
        $arForm = $rsForm->Fetch();
        $formId = $arForm["ID"];
        foreach ($fields as $field) {
            $is_filtered = false;
            $rsQuestions = \CFormField::GetList(
                $formId,
                "ALL",
                $by="s_id",
                $order="desc",
                ["SID" => $field],
                $is_filtered
            );
            if ($question = $rsQuestions->Fetch()) {
                $questionId = $question["ID"];
                $is_filtered = false;
                $filter = [];
                $rsAnswers = \CFormAnswer::GetList(
                    $questionId,
                    $by="s_id",
                    $order="desc",
                    $filter,
                    $is_filtered
                );
                if ($answer = $rsAnswers->Fetch()) {
                    $data["form_" . $answer["FIELD_TYPE"]. "_" . $answer["ID"]] = $values[$field];
                }
            }
        }
        $form = new \CFormResult();
        if ($RESULT_ID = $form->Add($formId, $data, "N")) {
            \CFormCRM::onResultAdded($formId, $RESULT_ID);
            \CFormResult::SetEvent($RESULT_ID);
            \CFormResult::Mail($RESULT_ID);
            return ["success" => true, "result" => $RESULT_ID];
        } else {
            global $strError;
            $this->addError(new Error("Ошибка отправки формы! ". $strError, 500));
            return null;
        }
    }

    public function requestPriceFormAction()
    {
        Loader::includeModule("form");
        $fields = [
            "name" => "NAME",
            "companyName" => "COMPANY_NAME",
            "phone" => "PHONE",
            "email" => "EMAIL",
            "comment" => "COMMENT",
            "page" => "PAGE",
            "crmId" => "CRM_ID"
        ];
        $requiredFields = [
            "NAME",
            "PHONE",
            "EMAIL",
            "PAGE",
            "CRM_ID"
        ];
        $values = [];
        $errors = [];
        $request = Context::getCurrent()->getRequest();
        foreach ($fields as $key => $field) {
            $values[$field] = $request->getPost($key);
            if (in_array($field, $requiredFields) && empty($values[$field])) {
                $this->addError(new Error(
                    "Не заполнено поле " . $key . "!",
                    401,
                    ["text" => "Не заполнено поле " . $key . "!", "code" => $key]
                ));
                $errors[] = $field;
            }
        }
        $check = $request->getPost("check");
        if ($check) {
            $this->addError(new Error("Не пройдена анти-спам проверка!", 401, ["text" => "Не пройдена анти-спам проверка!", "code" => "check"]));
        }
        if (count($errors) > 0) {
            return null;
        }
        $formSID = "REQUEST_PRICE";
        $rsForm = \CForm::GetBySID($formSID);
        $arForm = $rsForm->Fetch();
        $formId = $arForm["ID"];
        foreach ($fields as $field) {
            $is_filtered = false;
            $rsQuestions = \CFormField::GetList(
                $formId,
                "ALL",
                $by="s_id",
                $order="desc",
                ["SID" => $field],
                $is_filtered
            );
            if ($question = $rsQuestions->Fetch()) {
                $questionId = $question["ID"];
                $is_filtered = false;
                $filter = [];
                $rsAnswers = \CFormAnswer::GetList(
                    $questionId,
                    $by="s_id",
                    $order="desc",
                    $filter,
                    $is_filtered
                );
                if ($answer = $rsAnswers->Fetch()) {
                    $data["form_" . $answer["FIELD_TYPE"]. "_" . $answer["ID"]] = $values[$field];
                }
            }
        }
        $form = new \CFormResult();
        if ($RESULT_ID = $form->Add($formId, $data, "N")) {
            \CFormCRM::onResultAdded($formId, $RESULT_ID);
            \CFormResult::SetEvent($RESULT_ID);
            \CFormResult::Mail($RESULT_ID);
            return ["success" => true, "result" => $RESULT_ID];
        } else {
            global $strError;
            $this->addError(new Error("Ошибка отправки формы! ". $strError, 500));
            return null;
        }
    }

    public function requestResearchFormAction()
    {
        Loader::includeModule("form");
        $fields = [
            "name" => "NAME",
            "companyName" => "COMPANY_NAME",
            "phone" => "PHONE",
            "email" => "EMAIL",
            "comment" => "COMMENT",
            "page" => "PAGE"
        ];
        $requiredFields = [
            "NAME",
            "PHONE",
            "EMAIL",
            "PAGE"
        ];
        $values = [];
        $errors = [];
        $request = Context::getCurrent()->getRequest();
        foreach ($fields as $key => $field) {
            $values[$field] = $request->getPost($key);
            if (in_array($field, $requiredFields) && empty($values[$field])) {
                $this->addError(new Error(
                    "Не заполнено поле " . $key . "!",
                    401,
                    ["text" => "Не заполнено поле " . $key . "!", "code" => $key]
                ));
                $errors[] = $field;
            }
        }
        $check = $request->getPost("check");
        if ($check) {
            $this->addError(new Error("Не пройдена анти-спам проверка!", 401, ["text" => "Не пройдена анти-спам проверка!", "code" => "check"]));
        }
        if (count($errors) > 0) {
            return null;
        }
        $formSID = "REQUEST_FOR_RESEARCH";
        $rsForm = \CForm::GetBySID($formSID);
        $arForm = $rsForm->Fetch();
        $formId = $arForm["ID"];
        foreach ($fields as $field) {
            $is_filtered = false;
            $rsQuestions = \CFormField::GetList(
                $formId,
                "ALL",
                $by="s_id",
                $order="desc",
                ["SID" => $field],
                $is_filtered
            );
            if ($question = $rsQuestions->Fetch()) {
                $questionId = $question["ID"];
                $is_filtered = false;
                $filter = [];
                $rsAnswers = \CFormAnswer::GetList(
                    $questionId,
                    $by="s_id",
                    $order="desc",
                    $filter,
                    $is_filtered
                );
                if ($answer = $rsAnswers->Fetch()) {
                    $data["form_" . $answer["FIELD_TYPE"]. "_" . $answer["ID"]] = $values[$field];
                }
            }
        }
        $form = new \CFormResult();
        if ($RESULT_ID = $form->Add($formId, $data, "N")) {
            \CFormCRM::onResultAdded($formId, $RESULT_ID);
            \CFormResult::SetEvent($RESULT_ID);
            \CFormResult::Mail($RESULT_ID);
            return ["success" => true, "result" => $RESULT_ID];
        } else {
            global $strError;
            $this->addError(new Error("Ошибка отправки формы! ". $strError, 500));
            return null;
        }
    }

    public function subscribeReportFormAction()
    {
        Loader::includeModule("form");
        $fields = [
            "EMAIL",
            "SECTIONS",
        ];
        $requiredFields = [
            "EMAIL",
            "SECTIONS",
        ];
        $values = [];
        $errors = [];
        $request = Context::getCurrent()->getRequest();
        foreach ($fields as $field) {
            $values[$field] = $request->getPost(\mb_strtolower($field));
            if (in_array($field, $requiredFields) && empty($values[$field])) {
                $this->addError(new Error(
                    "Не заполнено поле " . \mb_strtolower($field) . "!",
                    401,
                    ["text" => "Не заполнено поле " . \mb_strtolower($field) . "!", "code" => \mb_strtolower($field)]
                ));
                $errors[] = $field;
            }
        }
        $check = $request->getPost("check");
        if ($check) {
            $this->addError(new Error("Не пройдена анти-спам проверка!", 401, ["text" => "Не пройдена анти-спам проверка!", "code" => "check"]));
        }
        if (count($errors) > 0) {
            return null;
        }
        $formSID = "SUBSCRIBE_FOR_REPORT";
        $rsForm = \CForm::GetBySID($formSID);
        $arForm = $rsForm->Fetch();
        $formId = $arForm["ID"];
        foreach ($fields as $field) {
            $is_filtered = false;
            $rsQuestions = \CFormField::GetList(
                $formId,
                "ALL",
                $by="s_id",
                $order="desc",
                ["SID" => $field],
                $is_filtered
            );
            if ($question = $rsQuestions->Fetch()) {
                $questionId = $question["ID"];
                $is_filtered = false;
                $filter = [];
                $rsAnswers = \CFormAnswer::GetList(
                    $questionId,
                    $by="s_id",
                    $order="desc",
                    $filter,
                    $is_filtered
                );
                if ($field == "SECTIONS") {
                    while ($answer = $rsAnswers->Fetch()) {
                        if (in_array($answer["VALUE"], $values[$field])) {
                            $data["form_" . $answer["FIELD_TYPE"]. "_" . $field][] = $answer["ID"];
                        }
                    }
                } else {
                    if ($answer = $rsAnswers->Fetch()) {
                        $data["form_" . $answer["FIELD_TYPE"]. "_" . $answer["ID"]] = $values[$field];
                    }
                }
            }
        }
        $form = new \CFormResult();
        if ($RESULT_ID = $form->Add($formId, $data, "N")) {
            \CFormCRM::onResultAdded($formId, $RESULT_ID);
            \CFormResult::SetEvent($RESULT_ID);
            \CFormResult::Mail($RESULT_ID);
            return ["success" => true, "result" => $RESULT_ID];
        } else {
            global $strError;
            $this->addError(new Error("Ошибка отправки формы! ". $strError, 500));
            return null;
        }
    }

    public function subscribeReportSectionsAction()
    {
        Loader::includeModule("form");
        $field = "SECTIONS";
        $formSID = "SUBSCRIBE_FOR_REPORT";
        $items = [];
        $rsForm = \CForm::GetBySID($formSID);
        $arForm = $rsForm->Fetch();
        $formId = $arForm["ID"];
        $is_filtered = false;
        $rsQuestions = \CFormField::GetList(
            $formId,
            "ALL",
            $by="s_id",
            $order="desc",
            ["SID" => $field],
            $is_filtered
        );
        if ($question = $rsQuestions->Fetch()) {
            $questionId = $question["ID"];
            $is_filtered = false;
            $filter = [];
            $rsAnswers = \CFormAnswer::GetList(
                $questionId,
                $by="s_id",
                $order="desc",
                $filter,
                $is_filtered
            );
            while ($answer = $rsAnswers->Fetch()) {
                $items[] = [
                    "name" => $answer["MESSAGE"],
                    "code" => $answer["VALUE"],
                ];
            }
        }

        return $items;
    }

    public function discussTaskListsAction()
    {
        Loader::includeModule("form");
        $fields = ["INTEREST", "TYPE"];
        $formSID = "DISCUSS_TASK";
        $items = [];
        $rsForm = \CForm::GetBySID($formSID);
        $arForm = $rsForm->Fetch();
        $formId = $arForm["ID"];
        foreach ($fields as $field) {
            $is_filtered = false;
            $key = \mb_strtolower($field);
            $rsQuestions = \CFormField::GetList(
                $formId,
                "ALL",
                $by="s_id",
                $order="desc",
                ["SID" => $field],
                $is_filtered
            );
            if ($question = $rsQuestions->Fetch()) {
                $questionId = $question["ID"];
                $is_filtered = false;
                $filter = [];
                $rsAnswers = \CFormAnswer::GetList(
                    $questionId,
                    $by="s_id",
                    $order="desc",
                    $filter,
                    $is_filtered
                );
                while ($answer = $rsAnswers->Fetch()) {
                    $items[$key][] = [
                        "name" => $answer["MESSAGE"],
                        "code" => $answer["VALUE"],
                    ];
                }
            }
        }

        return $items;
    }

    public function discussTaskFormAction()
    {
        Loader::includeModule("form");
        $fields = [
            "interest" => "INTEREST",
            "type" => "TYPE",
            "comment" => "COMMENT",
            "name" => "NAME",
            "phone" => "PHONE",
            "companyName" => "COMPANY_NAME",
            "email" => "EMAIL",
            "page" => "PAGE",
        ];
        $requiredFields = [
            "NAME",
            "PHONE",
            "EMAIL",
            "PAGE"
        ];
        $values = [];
        $errors = [];
        $request = Context::getCurrent()->getRequest();
        foreach ($fields as $key => $field) {
            $values[$field] = $request->getPost($key);
            if (in_array($field, $requiredFields) && empty($values[$field])) {
                $this->addError(new Error(
                    "Не заполнено поле " . $key . "!",
                    401,
                    ["text" => "Не заполнено поле " . $key . "!", "code" => $key]
                ));
                $errors[] = $field;
            }
        }
        $check = $request->getPost("check");
        if ($check) {
            $this->addError(new Error("Не пройдена анти-спам проверка!", 401, ["text" => "Не пройдена анти-спам проверка!", "code" => "check"]));
        }
        if (count($errors) > 0) {
            return null;
        }
        $formSID = "DISCUSS_TASK";
        $rsForm = \CForm::GetBySID($formSID);
        $arForm = $rsForm->Fetch();
        $formId = $arForm["ID"];
        foreach ($fields as $field) {
            $is_filtered = false;
            $rsQuestions = \CFormField::GetList(
                $formId,
                "ALL",
                $by="s_id",
                $order="desc",
                ["SID" => $field],
                $is_filtered
            );
            if ($question = $rsQuestions->Fetch()) {
                $questionId = $question["ID"];
                $is_filtered = false;
                $filter = [];
                $rsAnswers = \CFormAnswer::GetList(
                    $questionId,
                    $by="s_id",
                    $order="desc",
                    $filter,
                    $is_filtered
                );
                if ($field == "INTEREST" || $field == "TYPE") {
                    while ($answer = $rsAnswers->Fetch()) {
                        if ($answer["VALUE"] == $values[$field]) {
                            $data["form_" . $answer["FIELD_TYPE"]. "_" . $field] = $answer["ID"];
                            break;
                        }
                    }
                } else {
                    if ($answer = $rsAnswers->Fetch()) {
                        $data["form_" . $answer["FIELD_TYPE"]. "_" . $answer["ID"]] = $values[$field];
                    }
                }
            }
        }
        $form = new \CFormResult();
        if ($RESULT_ID = $form->Add($formId, $data, "N")) {
            \CFormCRM::onResultAdded($formId, $RESULT_ID);
            \CFormResult::SetEvent($RESULT_ID);
            \CFormResult::Mail($RESULT_ID);
            return ["success" => true, "result" => $RESULT_ID];
        } else {
            global $strError;
            $this->addError(new Error("Ошибка отправки формы! ". $strError, 500));
            return null;
        }
    }

}
