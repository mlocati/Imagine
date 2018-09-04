<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Test\Gmagick;

use Imagine\Gmagick\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;
use Imagine\Test\Image\AbstractImageTest;

/**
 * @group ext-gmagick
 */
class ImageTest extends AbstractImageTest
{
    protected function setUp()
    {
        parent::setUp();

        // disable GC while https://bugs.php.net/bug.php?id=63677 is still open
        // If GC enabled, Gmagick unit tests fail
        gc_disable();

        if (!class_exists('Gmagick')) {
            $this->markTestSkipped('Gmagick is not installed');
        }
    }

    public function provideFromAndToPalettes()
    {
        return array(
            array(
                'Imagine\Image\Palette\RGB',
                'Imagine\Image\Palette\CMYK',
                array(10, 10, 10),
            ),
            array(
                'Imagine\Image\Palette\CMYK',
                'Imagine\Image\Palette\RGB',
                array(10, 10, 10, 0),
            ),
        );
    }

    public function providePalettes()
    {
        return array(
            array('Imagine\Image\Palette\RGB', array(255, 0, 0)),
            array('Imagine\Image\Palette\CMYK', array(10, 0, 0, 0)),
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see \Imagine\Test\Image\AbstractImageTest::createImageForMask2()
     */
    protected function createImageForMask2()
    {
        $imagine = $this->getImagine();
        $rgb = new RGB();
        $image = $imagine->create(new Box(30, 30), $rgb->color('#f00'));

        return $image;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Imagine\Test\Image\AbstractImageTest::createMaskForMask2()
     */
    protected function createMaskForMask2(Box $size)
    {
        $imagine = $this->getImagine();
        $rgb = new RGB();
        $mask = $imagine->create($size, $rgb->color('#000'));
        $mask->draw()
            ->rectangle(new Point(0, 0), new Point(9, 9), $rgb->color('#fff'), true)
            ->rectangle(new Point(10, 0), new Point(19, 9), $rgb->color('#888'), true)
            ->rectangle(new Point(20, 0), new Point(29, 9), $rgb->color('#000'), true)
        ;

        return $mask;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Imagine\Test\Image\AbstractImageTest::getExpectedMaskPixels()
     */
    protected function getExpectedMaskPixels()
    {
        $rgb = new RGB();

        return array(
            array(new Point(5, 4), $rgb->color('#f00'), 0),
        );
    }

    /**
     * @group always-skipped
     *
     * {@inheritdoc}
     *
     * @see \Imagine\Test\Image\AbstractImageTest::testRotateWithTransparency()
     */
    public function testRotateWithTransparency()
    {
        $this->markTestSkipped('Alpha transparency is not supported by Gmagick');
    }

    /**
     * @group always-skipped
     *
     * {@inheritdoc}
     *
     * @see \Imagine\Test\Image\AbstractImageTest::testPaletteIsGrayIfGrayImage()
     */
    public function testPaletteIsGrayIfGrayImage()
    {
        $this->markTestSkipped('Gmagick does not support Gray colorspace, because of the lack omg image type support');
    }

    /**
     * @group always-skipped
     *
     * {@inheritdoc}
     *
     * @see \Imagine\Test\Image\AbstractImageTest::testGetColorAtCMYK()
     */
    public function testGetColorAtCMYK()
    {
        $this->markTestSkipped('Gmagick fails to read CMYK colors properly, see https://bugs.php.net/bug.php?id=67435');
    }

    /**
     * @group always-skipped
     *
     * {@inheritdoc}
     *
     * @see \Imagine\Test\Image\AbstractImageTest::testImageCreatedAlpha()
     */
    public function testImageCreatedAlpha()
    {
        $this->markTestSkipped('Alpha transparency is not supported by Gmagick');
    }

    /**
     * @group always-skipped
     *
     * {@inheritdoc}
     *
     * @see \Imagine\Test\Image\AbstractImageTest::testFillAlphaPrecision()
     */
    public function testFillAlphaPrecision()
    {
        $this->markTestSkipped('Alpha transparency is not supported by Gmagick');
    }

    /**
     * Alpha transparency is not supported by Gmagick.
     *
     * {@inheritdoc}
     *
     * @see \Imagine\Test\Image\AbstractImageTest::pasteWithAlphaProvider()
     */
    public function pasteWithAlphaProvider()
    {
        return array(
            array(0),
            array(100),
        );
    }

    protected function getImagine()
    {
        return new Imagine();
    }

    protected function getImageResolution(ImageInterface $image)
    {
        return $image->getGmagick()->getimageresolution();
    }

    protected function getSamplingFactors(ImageInterface $image)
    {
        return $image->getGmagick()->getSamplingFactors();
    }
}
