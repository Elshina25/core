<?php
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Routing\RoutingConfigurator;
use Bitrix\Main\Loader;
use Ita\Routes\Controllers\Test;
use Ita\Routes\Controllers\Research;
use Ita\Routes\Controllers\Forms;
use Ita\Routes\Controllers\Objects;
use Ita\Routes\Controllers\Service;
use Ita\Routes\Controllers\Awards;
use Ita\Routes\Controllers\Clients;
use Ita\Routes\Controllers\Projects;
use Ita\Routes\Controllers\Team;
use Ita\Routes\Controllers\Article;
use Ita\Routes\Controllers\News;
use Ita\Routes\Controllers\Vacancy;
use Ita\Routes\Controllers\Factoid;
use Ita\Routes\Controllers\Common;
use Ita\Routes\Controllers\Seo;

Loader::includeModule("ita.routes");

return function (RoutingConfigurator $routes) {
    // маршруты
	$routes->get('/api/hello/world/', [Test::class, 'hello']);
	$routes->get('/api/research/list/', [Research::class, 'list']); //Список исследований
    $routes->get('/api/research/sections/', [Research::class, 'sections']); //Список разделов для исследований
    $routes->get('/api/research/detail/{code}/', [Research::class, 'detail']); //Детальная исследования
    $routes->post('/api/forms/callback/', [Forms::class, 'callbackForm']); //бэкенд формы обратная связь
    $routes->post('/api/forms/research/', [Forms::class, 'researchForm']); //бэкенд формы исследования
	$routes->get('/api/objects/filter/', [Objects::class, 'objectsMap']); //фильтр объектов
	$routes->get('/api/objects/cities/', [Objects::class, 'getCities']); // Города
	$routes->get('/api/objects/types/', [Objects::class, 'getTypes']); //Типы помещений
    $routes->get('/api/objects/metros/', [Objects::class, 'getMetros']); //Метро
	$routes->get('/api/objects/сounty/', [Objects::class, 'getCounty']); //Округа
	$routes->get('/api/objects/county/raions/', [Objects::class, 'getCountiesAndRaions']);//Районы получаемые по округам и городу
	$routes->get('/api/service/list/', [Service::class, 'list']); // список услуг
	$routes->get('/api/service/forwhom/', [Service::class, 'getForWhom']); // список фильтра для кого услуги
	$routes->get('/api/service/types/', [Service::class, 'getTypes']);// список фильтров по типам
    $routes->get('/api/service/detail/{code}/', [Service::class, 'detail']); // детальная услуги
	$routes->get('/api/service/projectsdone/', [Service::class,'doneProjects']);// завершенные проекты на странице списка услуг
    $routes->post('/api/forms/help_in_selection/', [Forms::class, 'helpInSelectionForm']); //бэкенд формы Помощь в подборе
    $routes->post('/api/forms/request_for_view/', [Forms::class, 'requestForViewForm']); //бэкенд формы Заявка на просмотр
    $routes->post('/api/forms/request_price/', [Forms::class, 'requestPriceForm']); //бэкенд формы Запросить стоимость
    $routes->get('/api/projects/sections/', [Projects::class, 'sections']); //Список разделов для проектов
	$routes->get('/api/awards/', [Awards::class, 'getAwards']);// Награды
	$routes->get('/api/clients/', [Clients::class, 'getClients']);//Наши клиенты
	$routes->get('/api/team/', [Team::class, 'getTeam']); //Наша команда
    $routes->get('/api/projects/list/', [Projects::class, 'list']); //Список проектов
    $routes->get('/api/projects/detail/{code}/', [Projects::class, 'detail']); //Проект детально
    $routes->get('/api/objects/detail/{code}', [Objects::class, 'detail']); //Объект детально
	$routes->get('/api/blog/list/', [Article::class, 'getArticles']);// Список статей
	$routes->get('/api/blog/detail/{code}/', [Article::class, 'getDetail']);// Список статей
	$routes->get('/api/blog/immovables/', [Article::class, 'getImmovables']);// Виды недвижимости
	$routes->get('/api/blog/offers/', [Article::class, 'getOffers']);// Виды предложений
	$routes->get('/api/blog/serviceblock/', [Article::class, 'serviceSubjectBlock']);//блок разделов услуг в блоге
	$routes->get('/api/news/sections/', [News::class, 'sections']);// Виды новостей
	$routes->get('/api/news/list/', [News::class, 'list']);// Список новостей
	$routes->get('/api/news/detail/{code}/', [News::class, 'detail']);// Детальная новости
    $routes->post('/api/forms/request_for_research/', [Forms::class, 'requestResearchForm']); //бэкенд формы Запросить доступ к исследованию
    $routes->post('/api/forms/subscribe_for_report/', [Forms::class, 'subscribeReportForm']); //бэкенд формы Подписаться на отчёты
    $routes->get('/api/forms/subscribe_sections/', [Forms::class, 'subscribeReportSections']); //список разделов формы Подписаться на отчёты
    $routes->get('/api/forms/discuss_task_list/', [Forms::class, 'discussTaskLists']); //список разделов формы Подписаться на отчёты
    $routes->post('/api/forms/discuss_task/', [Forms::class, 'discussTaskForm']); //бэкенд формы Обсудить задачу
    $routes->get('/api/vacancy/list/', [Vacancy::class, 'list']); //список вакансий
	$routes->get('/api/vacancy/detail/{code}/', [Vacancy::class, 'detail']); //детальная вакансий
	$routes->get('/api/vacancy/workonus/list/', [Vacancy::class, 'workOnUs']); //блок работа у нас
	$routes->get('/api/vacancy/workonus/detail/{code}/', [Vacancy::class, 'workOnUsDetail']); //блок работа у нас
	$routes->get('/api/vacancy/workhistory/list/', [Vacancy::class, 'workersHistory']);// Истории сотрудников
	$routes->get('/api/vacancy/workhistory/topics/', [Vacancy::class, 'getTopics']); // Темы историй
	$routes->get('/api/vacancy/whatsweoffer/list/', [Vacancy::class, 'whatWeOffer']);// блок что мы предлагаем
	$routes->get('/api/vacancy/whatsweoffer/detail/{code}/', [Vacancy::class, 'whatWeOfferDetail']);// блок что мы предлагаем
	$routes->get('/api/factoids/', [Factoid::class, 'getFactoids']);//факториды
    $routes->get('/api/projects/list_fact/', [Projects::class, 'listFact']); //Список проектов с фактоидами
	$routes->get('/api/objects/coords/', [Objects::class, 'getCoords']);//метод показа объектов на карте
	$routes->get('/api/objects/object_preview/{id}/', [Objects::class, 'getObjectPreview']);//метод показа описания объекта на карте
	$routes->get('/api/objects/object_favorite/', [Objects::class, 'getObjectFavorite']);//метод показа описания объекта на карте
	$routes->get('/api/objects/name_search/', [Objects::class, 'searchNamePreview']);//метод показа списка объектов по названию
	$routes->get('/api/search/', [Common::class, 'search']);//метод поиска
	$routes->get('/api/search_types/', [Common::class, 'searchTypes']);//метод показа списка типов для поиска
    $routes->get('/api/objects/address_search/', [Objects::class, 'searchAddressPreview']);//метод показа списка адресов
    $routes->get('/api/objects/directions/', [Objects::class, 'getDirections']);//метод показа списка направлений
	$routes->get('/api/seo/text/', [Seo::class, 'getText']);//метод показа SEO-текста
};
