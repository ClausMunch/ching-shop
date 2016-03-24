<?php

namespace ChingShop\Http\Controllers\Staff;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Image\ImageRepository;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;

class ImageController extends Controller
{
    /** @var ViewFactory */
    private $viewFactory;

    /** @var ResponseFactory */
    private $responseFactory;

    /** @var ImageRepository */
    private $imageRepository;

    /**
     * ImageController constructor.
     *
     * @param ViewFactory     $viewFactory
     * @param ResponseFactory $responseFactory
     * @param ImageRepository $imageRepository
     */
    public function __construct(
        ViewFactory $viewFactory,
        ResponseFactory $responseFactory,
        ImageRepository $imageRepository
    ) {
        $this->viewFactory = $viewFactory;
        $this->responseFactory = $responseFactory;
        $this->imageRepository = $imageRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $images = $this->imageRepository->loadLatest(500);

        return $this->viewFactory->make(
            'staff.images.index',
            compact('images')
        );
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        $this->imageRepository->deleteById($id);

        return $this->redirectToImagesIndex();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function transferLocalImages()
    {
        $this->imageRepository->transferLocalImages();

        return $this->redirectToImagesIndex();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectToImagesIndex()
    {
        return $this->responseFactory->redirectToRoute(
            'staff.products.images.index'
        );
    }
}
