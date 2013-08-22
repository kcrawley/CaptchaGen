<?php namespace CaptchaGen;

class Config
{
    /**
     * @var array stores the config values which are valid and can be parsed
     */
    private $valid_modes = array('random', 'alphanum', 'arithmetic');

    /**
     * @var array stores the config values which relate to their generator Interface classes
     */
    private $generators = array(
        'arithmetic'    => '\CaptchaGen\ArithmeticGenerator',
        'alphanum'      => '\CaptchaGen\AlphaNumGenerator'
    );

    /**
     * @var bool stores whether or not the configuration has been checked for errors
     */
    private $configValidated;

    /**
     * @var bool stores whether or not the default settings have been set
     */
    private $defaultSet;

    /**
     * @var string valid modes are arithmetic, alphanum, and random
     */
    private $mode;

    /**
     * @var array constraints, expects 'min' => int(), 'max' => int()
     */
    private $length;

    /**
     * @var array expects full paths to background images, unlimited
     */
    private $background;

    /**
     * @var array expects full paths to truetype fonts, unlimited
     */
    private $font;

    /**
     * @var array constraints, expects 'min' => int(), 'max' => int()
     */
    private $font_size;

    /**
     * @var array expects HEX based color codes, unlimited
     */
    private $colors;

    /**
     * @var array constraints, expects 'min' => int(), 'max' => int()
     */
    private $angle;

    /**
     * set to null to disable shadows
     *
     * @var array expects 'color' => string() [hex], 'x' => int(), 'y' => int()
     */
    private $shadow;

    /**
     * @var string utilized by the Captcha library to process the generated challenge
     */
    private $challenge;

    /**
     * @var string utilized by the Captcha library to process the generated response
     * (only used in arithmetic mode)
     */
    private $response;

    /**
     * Sets the config parameters to default, and overrides if passed valid parameters.
     *
     * @param array $config_override Can be used to pass overrides
     */
    public function __construct(array $config_override = array())
    {
        $this->setDefaults();

        foreach($config_override as $key => $val) {
            if (property_exists($this, $key)) {
                $this->setKey($key, $val);
            }
        }

        $this->validateParams();
    }

    /**
     * Gets the requested configuration data
     *
     * @param $key
     * @return mixed
     */
    public function getKey($key)
    {
        if (property_exists($this, $key)) {
            return $this->$key;
        }
    }

    /**
     * Sets the requested configuration data
     *
     * @param $key
     * @param $value
     */
    public function setKey($key, $value)
    {
        if (property_exists($this, $key)) {
            $this->$key = $value;
            $this->validateParams(true);
        }
    }

    /**
     * Used by the constructor to set up default configuration data.
     */
    protected function setDefaults($override = false)
    {
        if ($override OR is_null($this->defaultSet)) {
            $this->mode         = 'random'; // valid modes are arithmetic, alphanum, and random
            $this->length       = array('min' => 5, 'max' => 5);
            $this->background   = array('lib/images/default.png');
            $this->font         = array('lib/fonts/times.ttf');
            $this->font_size    = array('min' => 24, 'max' => 30);
            $this->colors       = array('#000', '#3CB');
            $this->angle        = array('min' => 0, 'max' => 15);
            $this->shadow       = array('color' => '#CCC', 'x' => '-2', 'y' => '2');
        }

        $this->defaultSet = true;
    }

    /**
     * Keeps you crazy kids from doing anything wacky!
     */
    protected function validateParams($override = false)
    {
        if ($override OR is_null($this->configValidated)) {

            srand(microtime() * 100);

            if ( $this->length['min'] < 1 ) {
                $this->length['min'] = 1;
            }

            if (is_array($this->angle)) {
                if ( $this->angle['min'] < 0 ) {
                    $this->angle['min'] = 0;
                }

                if ( $this->angle['max'] > 10) {
                    $this->angle['max'] = 10;
                }

                if ( $this->angle['max'] < $this->angle['min'] ) {
                    $this->angle['max'] = $this->angle['min'];
                }

                $this->angle = rand( $this->angle['min'], $this->angle['max'] ) * (rand(0, 1) == 1 ? -1 : 1);
            }

            if (is_array($this->font_size)) {
                if ( $this->font_size['min'] < 10 ) {
                    $this->font_size['min'] = 10;
                }

                if ( $this->font_size['max'] < $this->font_size['min'] ) {
                    $this->font_size['max'] = $this->font_size['min'];
                }

                $this->font_size = rand($this->font_size['min'], $this->font_size['max']);
            }


            if ( in_array($this->mode, $this->valid_modes) === false ) {
                $this->mode = 'random';
            }

            // we will now choose random items if applicable

            if ( strpos($this->mode, 'random') === 0 ) {
                $rand_key = array_rand($this->generators);
                $this->mode = $rand_key;
            }

            if (is_array($this->background)) {
                $random = array_rand($this->background);
                $this->background = $this->background[$random];
            }

            if (is_array($this->font)) {
                $random = array_rand($this->font);
                $this->font = $this->font[$random];
            }

            $this->configValidated = true;
        }
    }
}