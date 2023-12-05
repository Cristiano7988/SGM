<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="telephone=no" name="format-detection">
    <title>Transações</title>
    <!--[if (mso 16)]>
    <style type="text/css">
    a {text-decoration: none;}
    </style>
    <![endif]-->
    <!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]-->
    <!--[if gte mso 9]>
<xml>
    <o:OfficeDocumentSettings>
    <o:AllowPNG></o:AllowPNG>
    <o:PixelsPerInch>96</o:PixelsPerInch>
    </o:OfficeDocumentSettings>
</xml>
<![endif]-->
</head>

<body>
    <div dir="ltr" class="es-wrapper-color" style="font-family: Arial, Helvetica, sans-serif; font-size: 80%; line-height: 150%;">
        <!--[if gte mso 9]>
			<v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
				<v:fill type="tile" color="#fafafa"></v:fill>
			</v:background>
		<![endif]-->
        <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0">
            <tbody>
                <tr>
                    <td class="esd-email-paddings" valign="top">
                        <table cellpadding="0" cellspacing="0" class="es-header esd-header-popover" align="center">
                            <tbody>
                                <tr>
                                    <td style="padding: 10px;" class="esd-stripe" align="center" esd-custom-block-id="388981" bgcolor="#efefef" style="background-color: #efefef;">
                                        <table class="es-header-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                            <tbody>
                                                <tr>
                                                    <td class="esd-structure es-p10t es-p10b es-p20r es-p20l" align="left">
                                                        <table cellpadding="0" cellspacing="0" width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="560" class="es-m-p0r esd-container-frame" valign="top" align="center">
                                                                        <table cellpadding="0" cellspacing="0" width="100%">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="center" class="esd-block-image es-p10t es-p10b" style="font-size: 0px;">
                                                                                        <a target="_blank">
                                                                                            <img src="http://tocacantocultural.com.br/img/logotipo/toca.png" alt style="display: block;" width="100">
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                            <tbody>
                                <tr>
                                    <td class="esd-stripe" align="center">
                                        <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                            <tbody>
                                                <tr>
                                                    <td class="esd-structure es-p15t es-p20b es-p20r es-p20l" align="left">
                                                        <table cellpadding="0" cellspacing="0" width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="560" class="esd-container-frame" align="center" valign="top">
                                                                        <table cellpadding="0" cellspacing="0" width="100%">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="center" class="esd-block-text es-p10b es-m-txt-c">
                                                                                        <h1 style="font-size: 38px; line-height: 100%;">Transações</h1>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" class="es-content" align="center">
                            <tbody>
                                <tr>
                                    <td class="esd-stripe" align="center">
                                        @foreach ($transacoes as $transacao)
                                        <table bgcolor="#ffffff" style="padding-bottom: 20px;" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600">
                                            <tbody>
                                                <tr>
                                                    <td class="esd-structure es-p20t es-p20r es-p20l" align="left">
                                                        <!--[if mso]><table width="560" cellpadding="0" cellspacing="0"><tr><td width="275" valign="top"><![endif]-->
                                                        <table cellpadding="0" cellspacing="0" class="es-left" align="left">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="275" class="es-m-p20b esd-container-frame" align="left">
                                                                        <table cellpadding="0" cellspacing="0" width="100%" style="border-width: 10px; border-style: solid; border-color: transparent; ">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="left" class="esd-block-text es-m-txt-c">
                                                                                        <h3>Transação #{{$transacao->id}}</h3>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="left" class="esd-block-text es-p5t es-p5b">
                                                                                        <p>
                                                                                            @if ($transacao->forma_de_pagamento) <strong>Forma de pagamento</strong>: {{$transacao->forma_de_pagamento->tipo}}<br> @endif
                                                                                            @if ($transacao->valor_pago) <strong>Valor</strong>: {{$transacao->valor_pago}}<br> @endif
                                                                                            @if ($transacao->data_de_pagamento) <strong>Data de pagamento</strong>: {{ App\Helpers\Formata::data($transacao->data_de_pagamento)}}<br> @endif
                                                                                            @if ($transacao->vigencia_do_pacote) <strong>Vigência</strong>: {{$transacao->vigencia_do_pacote}}<br> @endif
                                                                                            <hr />
                                                                                            @if ($transacao->user)
                                                                                                @if ($transacao->user->logradouro) <strong>Endereço</strong>: {{$transacao->user->logradouro}}<br> @endif
                                                                                                @if ($transacao->user->numero) <strong>Número</strong>: {{$transacao->user->numero}}<br> @endif
                                                                                                @if ($transacao->user->complemento) <strong>Complemento</strong>: {{$transacao->user->complemento}}<br> @endif
                                                                                                @if ($transacao->user->cep) <strong>CEP</strong>: {{$transacao->user->cep}}<br> @endif
                                                                                                @if ($transacao->user->caixa_postal) <strong>Caixa Postal</strong>: {{$transacao->user->caixa_postal}}<br> @endif
                                                                                                @if ($transacao->user->cidade) <strong>Bairro</strong>: {{$transacao->user->cidade}}<br> @endif
                                                                                                @if ($transacao->user->bairro) <strong>Cidade</strong>: {{$transacao->user->bairro}}<br> @endif
                                                                                                @if ($transacao->user->estado) <strong>Estado</strong>: {{$transacao->user->estado}}<br> @endif
                                                                                                @if ($transacao->user->pais) <strong>Pais</strong>: {{$transacao->user->pais}}<br> @endif
                                                                                            @endif
                                                                                        </p>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <!--[if mso]></td><td width="10"></td><td width="275" valign="top"><![endif]-->
                                                        <table cellpadding="0" cellspacing="0" class="es-right" align="right" style="background-color: #efefef;" bgcolor="#efefef;">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="275" align="left" class="esd-container-frame">
                                                                        <table cellpadding="0" cellspacing="0" width="100%" style="border-width: 10px; border-style: solid; border-color: transparent;">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="left" class="esd-block-text es-m-txt-c">
                                                                                        <h3>Dados para emissão da NF</h3>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="left" class="esd-block-text es-p5t es-p5b">
                                                                                        <p>
                                                                                            @if ($transacao->user)
                                                                                                @if ($transacao->user->nome) <strong>Nome</strong>: {{$transacao->user->nome}}<br> @endif
                                                                                                @if ($transacao->user->cpf) <strong>CPF</strong>: {{$transacao->user->cpf}}<br> @endif
                                                                                                @if ($transacao->user->cnpj) <strong>CNPJ</strong>: {{$transacao->user->cnpj}}<br> @endif
                                                                                                @if ($transacao->user->email_nf) <strong>Email</strong>: {{$transacao->user->email_nf}}<br> @endif
                                                                                                @if ($transacao->user->endereco) <strong>Endereço</strong>: {{$transacao->user->endereco}} @endif
                                                                                            @else
                                                                                                <i>Dados indisponíveis</i>    
                                                                                            @endif
                                                                                        </p>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <!--[if mso]></td></tr></table><![endif]-->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="esd-structure es-p10t es-p20b es-p20r es-p20l" align="left">
                                                        <table cellpadding="0" cellspacing="0" width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="560" class="es-m-p0r esd-container-frame" align="center">
                                                                        <table cellpadding="0" cellspacing="0" width="100%">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="center" class="esd-block-image" style="font-size: 0px;">
                                                                                        <a target="_blank" href>
                                                                                            <img class="adapt-img" src="http://127.0.0.1:8000/storage/{{$transacao->comprovante}}" alt style="display: block;" height="400">
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table cellpadding="0" cellspacing="0" class="es-footer esd-footer-popover" align="center">
                            <tbody>
                                <tr>
                                    <td class="esd-stripe" align="center" esd-custom-block-id="388980" bgcolor="#efefef" style="background-color: #efefef;">
                                        <table class="es-footer-body" align="center" cellpadding="0" cellspacing="0" width="640" style="background-color: transparent;">
                                            <tbody>
                                                <tr>
                                                    <td class="esd-structure es-p20t es-p20b es-p20r es-p20l" align="left">
                                                        <table cellpadding="0" cellspacing="0" width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="600" class="esd-container-frame" align="left">
                                                                        <table cellpadding="0" cellspacing="0" width="100%">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="center" class="esd-block-text es-p35b">
                                                                                        <p>Atenciosamente,</p>
                                                                                        <p>Juliana Sapper - Toca Canto Cultural</p>
                                                                                        <p><a target="_blank" href="https://tocacantocultural.com.br">https://tocacantocultural.com.br</a></p>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>