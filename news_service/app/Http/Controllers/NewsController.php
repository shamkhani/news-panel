<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsRequest;
use App\Models\News;
use App\Resources\NewsResource;
use App\Services\Common;
use App\Repositories\NewsRepositoryInterface;
use App\Services\NewsServiceInterface;
use Illuminate\Http\Request;

/**
 * Class NewsController
 * @package App\Http\Controllers
 */
class NewsController extends Controller
{
    /**
     * @var NewsServiceInterface
     */
    private $newsService;

    /**
     * NewsController constructor.
     * @param NewsServiceInterface $newsService
     */
    public function __construct(NewsServiceInterface $newsService)
    {
        $this->newsService = $newsService;
//        $this->middleware('custom_cache');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        try{
            if($request->ajax()) // This is check ajax request
            {
                $news = $this->newsService->getAllNewsByPagination();
                return NewsResource::collection($news);
            }
            return view('grutto.news.news.index');

//        }catch (\Exception $ex){
//            Common\Logger::logError($ex);
//            return  Common\ResponseService::error(500, $ex);
//        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        $newsCategory = $this->newsService->getAllNewsCategoryForNewsList();
        return view('grutto.news.news.new', compact('newsCategory'));
    }
    public function edit(News $news){
        dd($news->id);
        $newsCategory = $this->newsService->getAllNewsCategoryForNewsList();
        return view('grutto.news.news.edit', compact('news','newsCategory'));
    }

    /**
     * @param News $news
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(News $news)
    {
        try{
            return view('grutto.news.news.view', compact('news'));
        }catch (\Exception $ex){
            Common\Logger::logError($ex);
            return  Common\ResponseService::error(500, $ex);
        }
    }
    /**
     * @param NewsRequest $request
     */
    public function store(NewsRequest $request)
    {
        try{
            return Common\ResponseService::create($this->newsService->createNews($request->all()));
        }catch (\Exception $ex){
            Common\Logger::logError($ex);
            return  Common\ResponseService::error('Something went wrong!');
        }
    }

    /**
     * @param NewsRequest $request
     * @param $id
     */
    public function update(NewsRequest $request, $news)
    {
        try{
            return Common\ResponseService::success($this->newsService->updateNews($request->all(), $id));
        }catch (\Exception $ex){
            Common\Logger::logError($ex);
            return  Common\ResponseService::error('Something went wrong!');
        }
    }

    public function destroy($id)
    {
        try{
            $result = $this->newsService->removeNews($id);
            if($result){
                return Common\ResponseService::success($result, 'News has been delete');
            }
        }catch (\Exception $ex){
            Common\Logger::logError($ex);
            return  Common\ResponseService::error('Something went wrong!');
        }

    }
}
