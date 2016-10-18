<?php

namespace ChingShop\Modules\Catalogue\Http\Controllers\Staff;

use ChingShop\Http\Controllers\Controller;
use ChingShop\Http\WebUi;
use ChingShop\Image\ImageRepository;
use ChingShop\Modules\Catalogue\Http\Requests\Staff\PutImageAltTextRequest;

/**
 * Class ImageController.
 */
class ImageController extends Controller
{
    /** @var ImageRepository */
    private $imageRepository;

    /** @var WebUi */
    private $webUi;

    /**
     * ImageController constructor.
     *
     * @param ImageRepository $imageRepository
     * @param WebUi           $webUi
     */
    public function __construct(ImageRepository $imageRepository, WebUi $webUi)
    {
        $this->imageRepository = $imageRepository;
        $this->webUi = $webUi;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $images = $this->imageRepository->loadLatest();

        return $this->webUi->view('staff.images.index', compact('images'));
    }

    /**
     * @param int $imageId
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $imageId)
    {
        $this->imageRepository->deleteById($imageId);

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
     * @param int                    $imageId
     * @param PutImageAltTextRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function putImageAltText(
        int $imageId,
        PutImageAltTextRequest $request
    ) {
        $image = $this->imageRepository->loadById($imageId);

        $image->alt_text = $request->altText();
        $image->save();

        $this->webUi->successMessage(
            "Set the alt text for image {$image->id} to {$request->altText()}."
        );

        return $this->redirectToImagesIndex();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectToImagesIndex()
    {
        return $this->webUi->redirect('catalogue.staff.products.images.index');
    }
}
