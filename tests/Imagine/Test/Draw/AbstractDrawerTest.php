<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imagine\Test\Draw;

use Imagine\Image\Box;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;
use Imagine\Image\Point\Center;
use Imagine\Test\ImagineTestCase;

abstract class AbstractDrawerTest extends ImagineTestCase
{
    public function thicknessProvider()
    {
        return array(
            array(0),
            array(1),
            array(4),
        );
    }

    public function thicknessAndFillProvider()
    {
        $result = array();
        foreach ($this->thicknessProvider() as $thicknessData) {
            $result[] = array_merge($thicknessData, array(false));
            $result[] = array_merge($thicknessData, array(true));
        }

        return $result;
    }

    /**
     * * @dataProvider thicknessProvider
     *
     * @param int $thickness
     */
    public function testArc($thickness)
    {
        $imagine = $this->getImagine();
        $image = $imagine->create(new Box(40, 30), $this->getColor('fff'));
        $size = $image->getSize();
        $drawer = $image->draw();
        $this->assertSame($drawer, $drawer->arc(new Center($size), $size->scale(0.5), 0, 180, $this->getColor('f00')));
        $this->assertImageEquals($imagine->open("tests/Imagine/Fixtures/drawer/arc/thinkness{$thickness}.png"), $image, '', 0.134);
    }

    /**
     * * @dataProvider thicknessAndFillProvider
     *
     * @param int $thickness
     * @param bool $fill
     */
    public function testChord($thickness, $fill)
    {
        $imagine = $this->getImagine();
        $image = $imagine->create(new Box(60, 50), $this->getColor('fff'));
        if ($image instanceof \Imagine\Gd\Image && $fill) {
            $this->markTestSkipped('The GD Drawer can NOT draw correctly filled chords');
        }
        $fill01 = $fill ? 1 : 0;
        $size = $image->getSize();
        $drawer = $image->draw();
        $this->assertSame($drawer, $drawer->chord(new Center($size), $size->scale(0.8), 0, 240, $this->getColor('f00'), $fill, $thickness));
        $this->assertImageEquals($imagine->open("tests/Imagine/Fixtures/drawer/chord/thinkness{$thickness}-fill{$fill01}.png"), $image, '', 0.153);
    }

    /**
     * @dataProvider thicknessAndFillProvider
     *
     * @param bool $fill
     * @param int $thickness
     */
    public function testCircle($thickness, $fill)
    {
        $imagine = $this->getImagine();
        $image = $imagine->create(new Box(20, 20), $this->getColor('fff'));
        if ($image instanceof \Imagine\Gd\Image && !$fill && $thickness > 1) {
            $this->markTestSkipped('The GD Drawer can NOT draw correctly not filled circles with a thickness greater than 1');
        }

        $size = $image->getSize();
        $fill01 = $fill ? 1 : 0;
        $drawer = $image->draw();
        $this->assertSame($drawer, $drawer->circle(new Center($size), min($size->getWidth(), $size->getHeight()) * 0.9, $this->getColor('f00'), $fill, $thickness));
        $this->assertImageEquals($imagine->open("tests/Imagine/Fixtures/drawer/circle/thinkness{$thickness}-fill{$fill01}.png"), $image, '', 0.55);
    }

    /**
     * @dataProvider thicknessAndFillProvider
     *
     * @param bool $fill
     * @param int $thickness
     */
    public function testEllipse($thickness, $fill)
    {
        $imagine = $this->getImagine();
        $image = $imagine->create(new Box(30, 20), $this->getColor('fff'));
        if ($image instanceof \Imagine\Gd\Image && !$fill && $thickness > 1) {
            $this->markTestSkipped('The GD Drawer can NOT draw correctly not filled ellipses with a thickness greater than 1');
        }

        $size = $image->getSize();
        $fill01 = $fill ? 1 : 0;
        $drawer = $image->draw();
        $this->assertSame($drawer, $drawer->ellipse(new Center($size), $size->scale(0.9), $this->getColor('f00'), $fill, $thickness));
        $this->assertImageEquals($imagine->open("tests/Imagine/Fixtures/drawer/ellipse/thinkness{$thickness}-fill{$fill01}.png"), $image, '', 0.434);
    }

    /**
     * @dataProvider thicknessProvider
     *
     * @param bool $fill
     * @param mixed $thickness
     */
    public function testLine($thickness)
    {
        $imagine = $this->getImagine();
        $image = $imagine->create(new Box(30, 20), $this->getColor('fff'));
        $size = $image->getSize();
        $drawer = $image->draw();
        $this->assertSame($drawer, $drawer->line(new Point(5, 5), new Point($size->getWidth() - 5, $size->getHeight() - 6), $this->getColor('f00'), $thickness));
        $this->assertImageEquals($imagine->open("tests/Imagine/Fixtures/drawer/line/thinkness{$thickness}.png"), $image, '', 0.09);
    }

    /**
     * @dataProvider thicknessAndFillProvider
     *
     * @param bool $fill
     * @param int $thickness
     */
    public function testPieSlice($thickness, $fill)
    {
        $imagine = $this->getImagine();
        $image = $imagine->create(new Box(40, 40), $this->getColor('fff'));
        $size = $image->getSize();
        $fill01 = $fill ? 1 : 0;
        $drawer = $image->draw();
        $this->assertSame($drawer, $drawer->pieSlice(new Point($size->getWidth() / 2, 5), $size->scale(0.9), 45, 135, $this->getColor('f00'), $fill, $thickness));
        $this->assertImageEquals($imagine->open("tests/Imagine/Fixtures/drawer/pieslice/thinkness{$thickness}-fill{$fill01}.png"), $image, '', 0.095);
    }

    public function testDot()
    {
        $imagine = $this->getImagine();
        $image = $imagine->create(new Box(3, 3), $this->getColor('fff'));
        $drawer = $image->draw();
        $this->assertSame($drawer, $drawer->dot(new Point(1, 1), $this->getColor('f00')));
        $this->assertImageEquals($imagine->open('tests/Imagine/Fixtures/drawer/dot/dot.png'), $image, '', 0.095);
    }

    /**
     * @dataProvider thicknessAndFillProvider
     *
     * @param bool $fill
     * @param int $thickness
     */
    public function testRectangle($thickness, $fill)
    {
        $imagine = $this->getImagine();
        $image = $imagine->create(new Box(20, 25), $this->getColor('fff'));
        $size = $image->getSize();
        $fill01 = $fill ? 1 : 0;
        $drawer = $image->draw();
        $this->assertSame($drawer, $drawer->rectangle(new Point(5, 5), new Point($size->getWidth() - 5, $size->getHeight() - 5), $this->getColor('f00'), $fill, $thickness));
        $this->assertImageEquals($imagine->open("tests/Imagine/Fixtures/drawer/rectangle/thinkness{$thickness}-fill{$fill01}.png"), $image, '', 0.12);
    }

    /**
     * @dataProvider thicknessAndFillProvider
     *
     * @param bool $fill
     * @param int $thickness
     */
    public function testPolygon($thickness, $fill)
    {
        $imagine = $this->getImagine();
        $image = $imagine->create(new Box(25, 25), $this->getColor('fff'));
        $size = $image->getSize();
        $fill01 = $fill ? 1 : 0;
        $drawer = $image->draw();
        $this->assertSame($drawer, $drawer->polygon(
            array(
                new Point($size->getWidth() / 2, 5),
                new Point($size->getWidth() - 5, $size->getHeight() - 5),
                new Point(5, $size->getHeight() - 5),
            ),
            $this->getColor('f00'),
            $fill,
            $thickness
        ));
        $this->assertImageEquals($imagine->open("tests/Imagine/Fixtures/drawer/polygon/thinkness{$thickness}-fill{$fill01}.png"), $image, '', 0.154);
    }

    public function testText()
    {
        $imagine = $this->getImagine();
        $image = $imagine->create(new Box(60, 60), $this->getColor('fff'));
        $drawer = $image->draw();
        $this->assertSame($drawer, $drawer->text(
            'test',
            $imagine->font('tests/Imagine/Fixtures/font/Arial.ttf', 12, $this->getColor('f00')),
            new Point(3, 3),
            45
        ));
        $this->assertImageEquals($imagine->open('tests/Imagine/Fixtures/drawer/text/text.png'), $image, '', 0.049);
    }

    public function testDrawASmileyFace()
    {
        $imagine = $this->getImagine();

        $canvas = $imagine->create(new Box(400, 300), $this->getColor('000'));

        $canvas->draw()
            ->chord(new Point(200, 200), new Box(200, 150), 0, 180, $this->getColor('fff'), false)
            ->ellipse(new Point(125, 100), new Box(50, 50), $this->getColor('fff'))
            ->ellipse(new Point(275, 100), new Box(50, 50), $this->getColor('fff'), true);

        $canvas->save('tests/Imagine/Fixtures/smiley.png');

        $this->assertFileExists('tests/Imagine/Fixtures/smiley.png');

        unlink('tests/Imagine/Fixtures/smiley.png');
    }

    /**
     * @dataProvider drawARectangleProvider
     *
     * @param bool $fill
     * @param int $thickness
     */
    public function testDrawARectangle($fill, $thickness)
    {
        $imagine = $this->getImagine();
        $image = $imagine->create(new Box(15, 15), $this->getColor('fff'));
        $drawer = $image->draw();
        $this->assertSame($drawer, $drawer->rectangle(
            new Point(2, 2),
            new Point(12, 12),
            $this->getColor('f00'),
            $fill,
            $thickness
        ));
        $expected = $imagine->open('tests/Imagine/Fixtures/drawer/rectangle/thinkness' . $thickness . '-filled' . ($fill ? '1' : '0') . '.png');
        $this->assertImageEquals($expected, $image);
    }

    public function testDrawAPolygon()
    {
        $imagine = $this->getImagine();

        $canvas = $imagine->create(new Box(400, 300), $this->getColor('000'));

        $canvas->draw()
            ->polygon(array(
                new Point(50, 20),
                new Point(350, 20),
                new Point(350, 280),
                new Point(50, 280),
            ), $this->getColor('fff'), true);

        $canvas->save('tests/Imagine/Fixtures/polygon.png');

        $this->assertFileExists('tests/Imagine/Fixtures/polygon.png');

        unlink('tests/Imagine/Fixtures/polygon.png');
    }

    public function testDrawADot()
    {
        $imagine = $this->getImagine();

        $canvas = $imagine->create(new Box(400, 300), $this->getColor('000'));

        $canvas->draw()
            ->dot(new Point(200, 150), $this->getColor('fff'))
            ->dot(new Point(200, 151), $this->getColor('fff'))
            ->dot(new Point(200, 152), $this->getColor('fff'))
            ->dot(new Point(200, 153), $this->getColor('fff'));

        $canvas->save('tests/Imagine/Fixtures/dot.png');

        $this->assertFileExists('tests/Imagine/Fixtures/dot.png');

        unlink('tests/Imagine/Fixtures/dot.png');
    }

    public function testDrawText()
    {
        if (!$this->isFontTestSupported()) {
            $this->markTestSkipped('This install does not support font tests');
        }

        $path = 'tests/Imagine/Fixtures/font/Arial.ttf';
        $black = $this->getColor('000');
        $file36 = 'tests/Imagine/Fixtures/bulat36.png';
        $file24 = 'tests/Imagine/Fixtures/bulat24.png';
        $file18 = 'tests/Imagine/Fixtures/bulat18.png';
        $file12 = 'tests/Imagine/Fixtures/bulat12.png';

        $imagine = $this->getImagine();
        $canvas = $imagine->create(new Box(400, 300), $this->getColor('fff'));
        $font = $imagine->font($path, 36, $black);

        $canvas->draw()
            ->text('Bulat', $font, new Point(0, 0), 135);

        $canvas->save($file36);

        unset($canvas);

        $this->assertFileExists($file36);

        unlink($file36);

        $canvas = $imagine->create(new Box(400, 300), $this->getColor('fff'));
        $font = $imagine->font($path, 24, $black);

        $canvas->draw()
            ->text('Bulat', $font, new Point(24, 24));

        $canvas->save($file24);

        unset($canvas);

        $this->assertFileExists($file24);

        unlink($file24);

        $canvas = $imagine->create(new Box(400, 300), $this->getColor('fff'));
        $font = $imagine->font($path, 18, $black);

        $canvas->draw()
            ->text('Bulat', $font, new Point(18, 18));

        $canvas->save($file18);

        unset($canvas);

        $this->assertFileExists($file18);

        unlink($file18);

        $canvas = $imagine->create(new Box(400, 300), $this->getColor('fff'));
        $font = $imagine->font($path, 12, $black);

        $canvas->draw()
            ->text('Bulat', $font, new Point(12, 12));

        $canvas->save($file12);

        unset($canvas);

        $this->assertFileExists($file12);

        unlink($file12);
    }

    private function getColor($color)
    {
        static $palette;

        if (!$palette) {
            $palette = new RGB();
        }

        return $palette->color($color);
    }

    /**
     * @return ImagineInterface
     */
    abstract protected function getImagine();

    abstract protected function isFontTestSupported();
}
