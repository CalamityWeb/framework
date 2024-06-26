<?php
/**
 * @var $exception \Exception
 * @var $this      \calamity\common\models\core\View
 */

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title> <?= (!empty($this->title)) ? $this->title : 'ERROR - ' . $exception->getCode() ?> </title>

    <style>
        * {
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        .error-page {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            height: 100%;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
        }

        .error-page h1 {
            font-size: 30vh;
            font-weight: bold;
            position: relative;
            margin: -8vh 0 0;
            padding: 0;
        }

        .error-page h1:after {
            content: attr(data-h1);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            color: transparent;
            /* webkit only for graceful degradation to IE */
            background: -webkit-repeating-linear-gradient(-45deg, #71b7e6, #69a6ce, #b98acc, #ee8176, #b98acc, #69a6ce, #9b59b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 400%;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.25);
            animation: animateTextBackground 10s ease-in-out infinite;
        }

        .error-page h1 + p {
            color: #4B4B4B;
            font-size: 6vh;
            font-weight: bold;
            line-height: 10vh;
            max-width: 900px;
            position: relative;
        }

        .error-page h1 + p:after {
            content: attr(data-p);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            color: transparent;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.5);
            -webkit-background-clip: text;
            background-clip: text;
        }

        #particles-js {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        @keyframes animateTextBackground {
            0% {
                background-position: 0 0;
            }
            25% {
                background-position: 100% 0;
            }
            50% {
                background-position: 100% 100%;
            }
            75% {
                background-position: 0 100%;
            }
            100% {
                background-position: 0 0;
            }
        }

        @media (max-width: 767px) {
            .error-page h1 {
                font-size: 32vw;
            }

            .error-page h1 + p {
                font-size: 8vw;
                line-height: 10vw;
                max-width: 70vw;
            }
        }

        a.back {
            position: fixed;
            right: 40px;
            bottom: 40px;
            background: -webkit-repeating-linear-gradient(-45deg, #71b7e6, #69a6ce, #b98acc, #ee8176);
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            line-height: 24px;
            padding: 15px 30px;
            text-decoration: none;
            transition: 0.25s all ease-in-out;
        }

        a.back:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        }
    </style>
</head>
<body>
<div class="error-page">
    <div>
        <h1 data-h1="<?= $exception->getCode() ?>"><?= $exception->getCode() ?></h1>
        <p data-p="<?= $exception->getMessage() ?>"><?= $exception->getMessage() ?></p>
    </div>

    <div id="particles-js"></div>

    <a class="back" href="/ ">GO BACK</a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>

<script>
    particlesJS("particles-js", {
        "particles": {
            "number": {
                "value": 5,
                "density": {
                    "enable": true,
                    "value_area": 800
                }
            },
            "color": {
                "value": "#fcfcfc"
            },
            "shape": {
                "type": "circle",
            },
            "opacity": {
                "value": 0.5,
                "random": true,
                "anim": {
                    "enable": false,
                    "speed": 1,
                    "opacity_min": 0.2,
                    "sync": false
                }
            },
            "size": {
                "value": 140,
                "random": false,
                "anim": {
                    "enable": true,
                    "speed": 10,
                    "size_min": 40,
                    "sync": false
                }
            },
            "line_linked": {
                "enable": false,
            },
            "move": {
                "enable": true,
                "speed": 8,
                "direction": "none",
                "random": false,
                "straight": false,
                "out_mode": "out",
                "bounce": false,
                "attract": {
                    "enable": false,
                    "rotateX": 600,
                    "rotateY": 1200
                }
            }
        },
        "interactivity": {
            "detect_on": "canvas",
            "events": {
                "onhover": {
                    "enable": false
                },
                "onclick": {
                    "enable": false
                },
                "resize": true
            }
        },
        "retina_detect": true
    });
</script>
</body>
</html>