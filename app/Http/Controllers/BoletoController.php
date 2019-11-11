<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Storage;
use Carbon\Carbon;

class BoletoController extends TokenController
{
    public function registrar(Request $req){
        // return $req->all();
        //Homologação
        // https://cobranca.homologa.bb.com.br:7101/registrarBoleto

        $url = 'https://cobranca.homologa.bb.com.br:7101/registrarBoleto';
        $data = array(
            'convenio' => 1014051,
            'carteira' => 17,
            'variacao_carteira' => 19,
            'modalidade' => 1,
            'dt_emissao' => Carbon::now()->format('d.m.Y'),
            'dt_vencimento' => Carbon::now()->addDays(10)->format('d.m.Y'),
            'valor' => 30000,
            'tipo_desconto' => 1.
            
        );

        // Colocando os dados no XML para a requisição e salvando um arquivo XML com os dados
        $layout = view('boleto.layout-requisicao', compact('data'));
        Storage::disk('local')->put('requisicao.xml', $layout);
        // Recuperando o token para a requisição
        $token = json_decode(TokenController::gerar());

        $ch = curl_init();
		$options = array(
            CURLOPT_URL => $url,
			CURLOPT_BINARYTRANSFER => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_POST => true,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_POSTFIELDS => Storage::disk('local')->get('requisicao.xml'),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/xml;charset=UTF-8',
                "Authorization: Bearer $token->access_token",
                'SOAPAction: registrarBoleto'
            )
        );
        
        // $ch = curl_init();
        curl_setopt_array($ch, $options);

        $exe = curl_exec($ch);
        curl_close($ch);
        Storage::disk('local')->put('resposta.xml', $exe);

        // $file = Storage::disk('local')->get('resposta.xml');
        $xml = simplexml_load_string($exe);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        // return $exe;
        /*
            Representação que deve ser enviada vis POST para gerar o boleto
            {
                "valor_boleto": "3950,00",
                "nome_cliente": "Edvaldo Lima",
                "endereco": {
                    "rua":"Endereço do seu Cliente",
                    "estado": "RN",
                    "cidade": "Natal",
                    "cep": "59162-000"
                },
                "demonstrativo": {
                    "Atracação",
                    "Energia para container",
                    "Consumo de água"
                }
            }
        */
        // ------------------------- DADOS DINÂMICOS DO SEU CLIENTE PARA A GERAÇÃO DO BOLETO (FIXO OU VIA POST) -------------------- //
        // DADOS DO BOLETO PARA O SEU CLIENTE
        $dias_de_prazo_para_pagamento = 5;
        // $taxa_boleto = 2.95;
        $taxa_boleto = 0;
        $data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
        $valor_cobrado = $req['valor_boleto']; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
        $valor_cobrado = str_replace(",", ".",$valor_cobrado);
        $valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
        $dadosboleto["nosso_numero"] = "87654";
        $dadosboleto["numero_documento"] = "27.030195.10";	// Num do pedido ou do documento
        $dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
        $dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
        $dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
        $dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
        // DADOS DO SEU CLIENTE
        $dadosboleto["sacado"] = $req['nome_cliente'] ." - CNPJ/CPF: ". $req['cpf_cnpj'];
        $dadosboleto["endereco"] = array(
            $req['endereco']['rua'],
            $req['endereco']['cidade'] . " - " . $req['endereco']['estado'] . (!empty($req['endereco']['cep']) ? " - " . $req['endereco']['cep'] : "")
        );
        // INFORMACOES PARA O CLIENTE
        $dadosboleto["demonstrativo"] = $req['demonstrativo'];
        // INSTRUÇÕES PARA O CAIXA
        $dadosboleto["instrucoes"] = array(
            "- Sr. Caixa, cobrar multa de 2% após o vencimento",
            "- Receber até 10 dias após o vencimento",
            // "- Em caso de dúvidas entre em contato conosco: xxxx@xxxx.com.br"
        );
        // DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
        $dadosboleto["quantidade"] = "10";
        $dadosboleto["valor_unitario"] = "10";
        $dadosboleto["aceite"] = "N";		
        $dadosboleto["especie"] = "R$";
        $dadosboleto["especie_doc"] = "DM";
        // ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
        // DADOS DA SUA CONTA - BANCO DO BRASIL
        $dadosboleto["agencia"] = "9999"; // Num da agencia, sem digito
        $dadosboleto["conta"] = "99999"; 	// Num da conta, sem digito
        // DADOS PERSONALIZADOS - BANCO DO BRASIL
        $dadosboleto["convenio"] = "7777777";  // Num do convênio - REGRA: 6 ou 7 ou 8 dígitos
        $dadosboleto["contrato"] = "999999"; // Num do seu contrato
        $dadosboleto["carteira"] = "18";
        $dadosboleto["variacao_carteira"] = "-019";  // Variação da Carteira, com traço (opcional)
        // TIPO DO BOLETO
        $dadosboleto["formatacao_convenio"] = "7"; // REGRA: 8 p/ Convênio c/ 8 dígitos, 7 p/ Convênio c/ 7 dígitos, ou 6 se Convênio c/ 6 dígitos
        $dadosboleto["formatacao_nosso_numero"] = "2"; // REGRA: Usado apenas p/ Convênio c/ 6 dígitos: informe 1 se for NossoNúmero de até 5 dígitos ou 2 para opção de até 17 dígitos
        /*
        #################################################
        DESENVOLVIDO PARA CARTEIRA 18
        - Carteira 18 com Convenio de 8 digitos
        Nosso número: pode ser até 9 dígitos
        - Carteira 18 com Convenio de 7 digitos
        Nosso número: pode ser até 10 dígitos
        - Carteira 18 com Convenio de 6 digitos
        Nosso número:
        de 1 a 99999 para opção de até 5 dígitos
        de 1 a 99999999999999999 para opção de até 17 dígitos
        #################################################
        */
        // SEUS DADOS
        $dadosboleto["identificacao"] = "COMPANHIA DOCAS DO RIO GRANDE DO NORTE-CODERN";
        $dadosboleto["cpf_cnpj"] = "34.040.345/0001-90";
        $dadosboleto["endereco_cedente"] = "Av. Eng Hildebrando de Góis, 220 - Ribeira | CEP: 59010-700";
        $dadosboleto["cidade_uf"] = "Natal/RN";
        $dadosboleto["cedente"] = "COMPANHIA DOCAS DO RIO GRANDE DO NORTE-CODERN";
        // NÃO ALTERAR!
        include("include/funcoes_bb.php"); 
        // include("include/layout_bb.php");

        // $html2pdf = new Html2Pdf('P', 'A4', 'en');
        // $html2pdf->writeHTML($loadLayout);
        // $html2pdf->output();

        // echo view('pdf/layout-boleto/layout', compact('dadosboleto'));

        return response(view('pdf/layout-boleto/layout', compact('dadosboleto')), 200)
                  ->header('Content-Type', 'text/html');

        // return response()->json($req->all(), 200);

    }
}
