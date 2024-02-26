<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Forpro | Confirmation de Rendez-vous</title>
    <style>
        /* -------------------------------------
            GLOBAL RESETS
        ------------------------------------- */
        img {
            border: none;
            -ms-interpolation-mode: bicubic;
            max-width: 100%;
        }

        body {
            background-color: #ffffff;
            font-family: sans-serif;
            -webkit-font-smoothing: antialiased;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        table {
            border-collapse: separate;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            width: 100%;
        }

        table td {
            font-family: sans-serif;
            font-size: 14px;
            vertical-align: top;
        }

        /* -------------------------------------
            BODY & CONTAINER
        ------------------------------------- */

        .body {
            background-color: white;
            width: 100%;
        }

        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
        .container {
            display: block;
            Margin: 0 auto !important;
            /* makes it centered */
            max-width: 580px;
            padding: 10px;
            width: 580px;
        }

        /* This should also be a block element, so that it will fill 100% of the .container */
        .content {
            box-sizing: border-box;
            display: block;
            Margin: 0 auto;
            max-width: 580px;
            padding: 10px;
        }

        /* -------------------------------------
            HEADER, FOOTER, MAIN
        ------------------------------------- */
        .main {
            background: #E3D4BE;
            border-radius: 3px;
            width: 100%;
        }

        .wrapper {
            box-sizing: border-box;
            padding: 20px;
        }

        .content-block {
            padding-bottom: 10px;
            padding-top: 10px;
        }

        .footer {
            clear: both;
            Margin-top: 10px;
            text-align: center;
            width: 100%;
        }

        .footer td,
        .footer p,
        .footer span,
        .footer a {
            color: #999999;
            font-size: 12px;
            text-align: center;
        }

        /* -------------------------------------
            TYPOGRAPHY
        ------------------------------------- */
        h1,
        h2,
        h3,
        h4 {
            color: #000000;
            font-family: sans-serif;
            font-weight: 400;
            line-height: 1.4;
            margin: 0;
            Margin-bottom: 30px;
        }

        h1 {
            font-size: 35px;
            font-weight: 300;
            text-align: center;
            text-transform: capitalize;
        }

        p,
        ul,
        ol {
            font-family: sans-serif;
            font-size: 14px;
            font-weight: normal;
            margin: 0;
            Margin-bottom: 15px;
        }

        p li,
        ul li,
        ol li {
            list-style-position: inside;
            margin-left: 5px;
        }

        a {
            color: #ed8034;
            text-decoration: underline;
        }

        /* -------------------------------------
            BUTTONS
        ------------------------------------- */
        .btn {
            box-sizing: border-box;
            width: 100%;
        }

        .btn > tbody > tr > td {
            padding-bottom: 15px;
        }

        .btn table {
            width: auto;
        }

        .btn table td {
            background-color: #ffffff;
            border-radius: 5px;
            text-align: center;
        }

        .btn a {
            background-color: #ffffff;
            border: solid 1px #ed8034;
            border-radius: 5px;
            box-sizing: border-box;
            color: #1754FF;
            cursor: pointer;
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            padding: 12px 25px;
            text-decoration: none;
            text-transform: capitalize;
        }

        .btn-primary table td {
            background-color: #1754FF;
        }

        .btn-primary a {
            background-color: #1754FF;
            border-color: #1754FF;
            color: #ffffff;
        }

        /* -------------------------------------
            OTHER STYLES THAT MIGHT BE USEFUL
        ------------------------------------- */
        .last {
            margin-bottom: 0;
        }

        .first {
            margin-top: 0;
        }

        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        .align-left {
            text-align: left;
        }

        .clear {
            clear: both;
        }

        .mt0 {
            margin-top: 0;
        }

        .mb0 {
            margin-bottom: 0;
        }

        .preheader {
            color: transparent;
            display: none;
            height: 0;
            max-height: 0;
            max-width: 0;
            opacity: 0;
            overflow: hidden;
            mso-hide: all;
            visibility: hidden;
            width: 0;
        }

        .powered-by a {
            text-decoration: none;
        }

        hr {
            border: 0;
            border-bottom: 1px solid #f6f6f6;
            Margin: 20px 0;
        }

        /* -------------------------------------
            RESPONSIVE AND MOBILE FRIENDLY STYLES
        ------------------------------------- */
        @media only screen and (max-width: 620px) {
            table[class=body] h1 {
                font-size: 28px !important;
                margin-bottom: 10px !important;
            }

            table[class=body] p,
            table[class=body] ul,
            table[class=body] ol,
            table[class=body] td,
            table[class=body] span,
            table[class=body] a {
                font-size: 16px !important;
            }

            table[class=body] .wrapper,
            table[class=body] .article {
                padding: 10px !important;
            }

            table[class=body] .content {
                padding: 0 !important;
            }

            table[class=body] .container {
                padding: 0 !important;
                width: 100% !important;
            }

            table[class=body] .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
            }

            table[class=body] .btn table {
                width: 100% !important;
            }

            table[class=body] .btn a {
                width: 100% !important;
            }

            table[class=body] .img-responsive {
                height: auto !important;
                max-width: 100% !important;
                width: auto !important;
            }
        }

        /* -------------------------------------
            PRESERVE THESE STYLES IN THE HEAD
        ------------------------------------- */
        @media all {
            .ExternalClass {
                width: 100%;
            }

            .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
                line-height: 100%;
            }

            .apple-link a {
                color: inherit !important;
                font-family: inherit !important;
                font-size: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                text-decoration: none !important;
            }

            .btn-primary table td:hover {
                background-color: #34495e !important;
            }

            .btn-primary a:hover {
                background-color: #34495e !important;
                border-color: #34495e !important;
            }
        }

    </style>
</head>
<body class="">
<table cellpadding="0" cellspacing="0" class="body">
    <tr>
        <td>&nbsp;</td>
        <td class="container">
            <div class="align-center" style="margin: 40px;">
                <svg id="Calque_2" data-name="Calque 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 297.36379 55.34905" style="fill: black;">
                    <defs>
                        <style>
                            .cls-1 {
                                fill: #3df069;
                            }

                            .cls-2 {
                                fill: #1754ff;
                            }
                        </style>
                    </defs>
                    <g id="Layer_1" data-name="Layer 1">
                        <g>
                            <g>
                                <path class="cls-2" d="M66.4321,49.34315c-11.3213,0-20.3144-9.1084-20.2343-21.7012,.081-12.7548,9.0722-21.7841,20.2343-21.7841,11.2408,0,20.314,9.0293,20.314,21.7841,0,12.5928-8.9121,21.7012-20.314,21.7012"/>
                                <path class="cls-1" d="M269.65089,48.02095c-10.4903,0-18.8242-8.5547-18.75-20.3819,.0762-11.9785,8.4082-20.4599,18.75-20.4599,10.416,0,18.8222,8.4814,18.8222,20.4599,0,11.8272-8.2578,20.3819-18.8222,20.3819"/>
                                <path class="cls-1" d="M66.4346,48.05895c-10.4898,0-18.8228-8.5546-18.7486-20.3818,.0747-11.9785,8.4068-20.46,18.7486-20.46,10.416,0,18.8232,8.4815,18.8232,20.46,0,11.8272-8.2578,20.3818-18.8232,20.3818M66.4346,.00035C51.0337,.00035,39.4277,12.12735,39.4277,27.75135c0,15.7686,11.5323,27.5977,27.0069,27.5977,15.5503,0,27.082-11.8291,27.082-27.5977C93.5166,12.12735,81.8359,.00035,66.4346,.00035"/>
                            </g>
                            <polygon points="0 1.04145 0 54.15855 7.6631 54.15855 7.6631 31.17325 32.438 31.17325 32.438 23.88125 7.6631 23.88125 7.6631 8.40765 34.4468 8.40765 34.4468 1.04145 0 1.04145"/>
                            <g>
                                <path d="M214.93209,24.47795h-7.3672V12.72205h7.0694c4.9111,0,7.8115,2.3076,7.8115,5.8037,0,3.4971-2.6035,5.9522-7.5137,5.9522m19.2696-6.5479c0-10.6386-9.4483-16.8886-19.7168-16.8886h-18.5987V54.15855h11.6787v-19.1181h5.5079l14.3574,19.1181h13.3926l-16.1436-21.4218c5.5049-2.6045,9.5225-7.291,9.5225-14.8067"/>
                                <path d="M109.6572,25.37055V8.33345h9.0757c6.10161,0,10.7149,2.6777,10.7149,8.4072,0,6.1748-4.6875,8.6299-10.7149,8.6299h-9.0757Zm27.5288-8.6299c0-10.7881-8.9287-15.6992-18.6757-15.6992h-16.4419V54.15855h7.5888v-21.498h6.7701l16.666,21.498h9.5224l-17.6328-22.538c5.7295-1.1153,12.2031-6.4727,12.2031-14.8799"/>
                                <path class="cls-2" d="M269.68799,43.89295c-8.1104,0-15.4014-6.1738-15.4014-16.2158,0-10.4912,7.3662-16.2939,15.4014-16.2939s15.4746,5.8779,15.4746,16.2939c0,10.042-7.3672,16.2158-15.4746,16.2158M269.68799,.00035c-15.4766,.0743-27.6787,12.127-27.6787,27.6768,0,15.917,12.94721,27.6719,27.6787,27.5986,14.72951-.0742,27.6758-11.6816,27.6758-27.5986C297.36379,12.12735,285.16259-.07485,269.68799,.00035"/>
                                <path d="M168.8071,25.81775h-7.0664V12.57365h7.0664c5.4336,0,8.7793,2.456,8.7793,6.5478,0,3.794-3.3457,6.6963-8.7793,6.6963m-.3711-24.7763h-18.3759V54.15855h11.6806v-17.2568h7.3653c11.3828,0,20.0136-7.3633,20.0136-17.9297,0-12.1269-9.4492-17.9306-20.6836-17.9306"/>
                            </g>
                        </g>
                    </g>
                </svg>
            </div>
            <div class="content">
                <!-- START CENTERED WHITE CONTAINER -->
                <span class="preheader">Confirmez votre rendez-vous en cliquant sur le bouton ci-dessous !</span>
                <table class="main">

                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                        <td class="wrapper align-center">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <p>Votre rendez-vous avec ForPro est prévu</p>

                                        <div class="align-center">
                                            <p><?= $serviceName ?></p>
                                            <p>
                                                <b>Date et Heure : <?= $startDate ?> à <?= $startTime ?></b>
                                            </p>
                                        </div>

                                        <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                                            <tbody>
                                            <tr>
                                                <td align="center">
                                                    <table border="0" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                        <tr>
                                                            <td><a href="<?= $validationURL ?>" target="_blank">Je confirme !</a></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <p>
                                            Une erreur dans la prise de rendez-vous&nbsp;? Il vous suffit simplement de <a href="https://forpro-website.sdrvl.ch/rendez-vous" target="_blank">reprendre rendez-vous ici.</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- END MAIN CONTENT AREA -->
                </table>

                <!-- START FOOTER -->
                <div class="footer">
                    <table border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="content-block">
                                <a href="https://for-pro.ch">Votre équipe Forpro !</a>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- END FOOTER -->

                <!-- END CENTERED WHITE CONTAINER -->
            </div>
        </td>
        <td>&nbsp;</td>
    </tr>
</table>
</body>
</html>
