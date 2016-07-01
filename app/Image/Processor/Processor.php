<?php
namespace Hackaton\ImageStorage\Image\Processor;

use Hackaton\ImageStorage\Exceptions\CommandNotFoundException;
use Hackaton\ImageStorage\Image\Processor\Commands\ICommand;
use Nette\Utils\Image;

class Processor
{

    /**
     * @param $name
     * @return ICommand
     * @throws CommandNotFoundException
     */
    protected function createCommand($name)
    {
        $name = ucfirst($name);
        $class_name = "\\Hackaton\\ImageStorage\\Image\\Processor\\Commands\\{$name}Command";

        if (!class_exists($class_name)) {
            throw new CommandNotFoundException(sprintf('Command "%s" not found. (%s)', $name, $class_name));
        }

        return new $class_name;
    }



    public function applyCommands(Image $image, array $commands = [])
    {
        foreach ($commands as $command_data) {
            $name = array_shift($command_data);
            $command = $this->createCommand($name);
            $image = $command->execute($image, $command_data);
        }

        return $image;
    }


    /**
     * @param Image $image
     */
    public function cropBackground(Image &$image)
    {
        $img = $image->getImageResource();

        for ($b_top = 0; $b_top < imagesy($img); ++$b_top) {
            for ($x = 0; $x < imagesx($img); ++$x) {
                if (imagecolorat($img, $x, $b_top) != 0xFFFFFF) {
                    break 2; //out of the 'top' loop
                }
            }
        }

        for ($b_btm = 0; $b_btm < imagesy($img); ++$b_btm) {
            for ($x = 0; $x < imagesx($img); ++$x) {
                if (imagecolorat($img, $x, imagesy($img) - $b_btm - 1) != 0xFFFFFF) {
                    break 2; //out of the 'bottom' loop
                }
            }
        }

        for ($b_lft = 0; $b_lft < imagesx($img); ++$b_lft) {
            for ($y = 0; $y < imagesy($img); ++$y) {
                if (imagecolorat($img, $b_lft, $y) != 0xFFFFFF) {
                    break 2; //out of the 'left' loop
                }
            }
        }

        for ($b_rt = 0; $b_rt < imagesx($img); ++$b_rt) {
            for ($y = 0; $y < imagesy($img); ++$y) {
                if (imagecolorat($img, imagesx($img) - $b_rt - 1, $y) != 0xFFFFFF) {
                    break 2; //out of the 'right' loop
                }
            }
        }

        $newimg = imagecreatetruecolor(imagesx($img) - ($b_lft + $b_rt), imagesy($img) - ($b_top + $b_btm));
        imagecopy($newimg, $img, 0, 0, $b_lft, $b_top, imagesx($newimg), imagesy($newimg));


        $image = new Image($newimg);
    }
}
