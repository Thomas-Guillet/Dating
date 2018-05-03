<?php

include_once '../../../config/constantes.php';
include_once CONNEXION;
include_once MODELS.'mails.php';
include_once MODELS.'comptes.php';
include_once CONTROLLERS.'fonctions.php';

//On récupère le mail
$aMail = getMailByMail($_POST['mail']);
if ($aMail) {
	//On récupère le compte
	$aCompte = getComptebyIdMail($aMail['id_mail']);
	if ($aCompte) {
		//On génère un code pour la récupération du mot de passe
		$sCode = genererCodePassword();
		//On enregistre le code
		updateCodePassword($aCompte['id_compte'], $sCode);
		//On écrit le mail
		$sObjet = 'Alors, on oublie son mot de passe ?';
		$sMessage = '
		    <!DOCTYPE html>
		    <html lang="fr">
		    <head>
		        <meta charset="utf-8">
		        <meta name="viewport" content="width=device-width">
		        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		        <title>'.$sObjet.'</title>
		        
		        <style>

		            html,
		            body {
		                margin: 0 auto !important;
		                padding: 0 !important;
		                height: 100% !important;
		                width: 100% !important;
		                font-family: "Open Sans",sans-serif;
		            }
		            
		            * {
		                -ms-text-size-adjust: 100%;
		                -webkit-text-size-adjust: 100%;
		            }
		            
		            div[style*="margin: 16px 0"] {
		                margin:0 !important;
		            }
		            
		            table,
		            td {
		                mso-table-lspace: 0pt !important;
		                mso-table-rspace: 0pt !important;
		            }
		                    
		            table {
		                border-spacing: 0 !important;
		                border-collapse: collapse !important;
		                table-layout: fixed !important;
		                margin: 0 auto !important;
		            }
		            table table table {
		                table-layout: auto; 
		            }
		            
		            img {
		                -ms-interpolation-mode:bicubic;
		            }
		            
		            .mobile-link--footer a,
		            a[x-apple-data-detectors] {
		                color:inherit !important;
		                text-decoration: underline !important;
		            }
		          
		        </style>
		        
		        <style>
		            
		            .button-td,
		            .button-a {
		                transition: all 100ms ease-in;
		            }
		            .button-td:hover,
		            .button-a:hover {
		                background: #007CFF !important;
		                border-color: #007CFF !important;
		            }

		            @media screen and (max-width: 480px) {

		                .fluid,
		                .fluid-centered {
		                    width: 100% !important;
		                    max-width: 100% !important;
		                    height: auto !important;
		                    margin-left: auto !important;
		                    margin-right: auto !important;
		                }
		                .fluid-centered {
		                    margin-left: auto !important;
		                    margin-right: auto !important;
		                }

		                .stack-column,
		                .stack-column-center {
		                    display: block !important;
		                    width: 100% !important;
		                    max-width: 100% !important;
		                    direction: ltr !important;
		                }
		                .stack-column-center {
		                    text-align: center !important;
		                }
		            
		                .center-on-narrow {
		                    text-align: center !important;
		                    display: block !important;
		                    margin-left: auto !important;
		                    margin-right: auto !important;
		                    float: none !important;
		                }
		                table.center-on-narrow {
		                    display: inline-block !important;
		                }
		                    
		            }

		        </style>

		    </head>
		    <body width="100%" bgcolor="lightgray" style="margin: 0;">
		        <center style="width: 100%; background: lightgray;">

		            <div style="max-width: 680px; margin: auto;">

		                <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px;">
		                    <tr>
		                        <td style="padding: 20px 0; text-align: center; color: #007CFF; font-size: 30px;">
		                            <b>Uprigs</b>
		                        </td>
		                    </tr>
		                </table>
		                
		                <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px;">

		                    <tr>
		                        <td bgcolor="#ffffff">
		                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
		                                <tr>
		                                    <td style="padding: 40px; padding-bottom: 0px; text-align: center; font-size: 15px; mso-height-rule: exactly; color: #555555;">
		                                        <p style="line-height: 30px; font-size:30px; color:#007CFF;">
		                                            <b>'.$sObjet.'</b>
		                                        </p>
		                                        <p>
		                                            Ce n\'est pas grave, voici un lien pour en créer un nouveau :<br />
		                                            <a href="'.URL.'tu-veux-modifier-ton-mot-de-passe/'.$_POST['mail'].'/'.$sCode.'">Créer mon nouveau mot de passe</a>
		                                        </p>
		                                        <p>
		                                            Petite astuce pour le retenir facilement,<br />
		                                            se connecter quotidiennement sur <span style="color:#0028C9">Uprigs</span>.
		                                        </p>
		                                        <p>A tout de suite !</p>
		                                    </td>           
		                                </tr>
		                            </table>
		                            <br /><br />
		                        </td>
		                    </tr>
		                </table>    
		                <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px;">
		                    <tr>
		                        <td style="padding: 20px 10px;width: 100%;font-size: 12px; mso-height-rule: exactly; line-height:18px; text-align: center; color: dimgray;">
		                            Ce message est envoyé automatiquement.<br />Veuillez ne pas y répondre.
		                            <br><br>
		                        </td>
		                    </tr>
		                </table>      
		            </div>
		        </center>
		    </body>
		    </html>
		';
		$sHeaders  = 'MIME-Version: 1.0' . "\r\n";
     	$sHeaders .= 'Content-type: text/html; charset=utf-8' . "\r\n";
     	$sHeaders .= 'From: Uprigs <info@uprigs.com>' . "\r\n";
     	if (mail($_POST['mail'], $sObjet, $sMessage, $sHeaders)) {
			//echo $sMessage;
     		echo 'Uprigs vient d\'envoyer la marche à suivre par email';
     	}
	} else {
		echo 'Aucun compte ne correspond à cet email';
	}
} else {
	echo 'Uprigs ne connait pas cet email';
}

?>