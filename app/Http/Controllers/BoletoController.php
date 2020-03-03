<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Storage;
use Carbon\Carbon;
use SimpleXMLElement;
use App\Boletos;
use DB;

class BoletoController extends TokenController
{
    public function registrar(Request $req){
        // return $req->all();
        //Homologação
        // https://cobranca.homologa.bb.com.br:7101/registrarBoleto

        $url = 'https://cobranca.homologa.bb.com.br:7101/registrarBoleto';

        // Gerando o nosso número = ano + num fatura
        // Pega o registro máximo registrado no banco
        // $calc = "0000000004" + 1;
        // $len = str_pad($calc, 10, "0", STR_PAD_LEFT);
        // echo $len;
        // $registro = DB::table('boletos')->max('nossoNumero');

        // return $registro;

        $data = array(
            'convenio' => 2805774,
            'carteira' => 17,
            'variacao_carteira' => 27,
            'modalidade' => 1,
            'dt_emissao' => Carbon::now()->format('d.m.Y'),
            'dt_vencimento' => Carbon::now()->addDays(10)->format('d.m.Y'),
            'valor_boleto' => 30000,
            'tipo_desconto' => 1,
            'nome_cliente' => "DATACOM",
            'cpf_cnpj' => "70122808401",
            'endereco' => array(
                'rua' => "Rua Edite Pereira",
                'cidade' => "Natal",
                'estado' => "RN",
                'cep' => "59162-000"
            ),
            'demonstrativo' => array(
                "Foi feito isso e aquilo",
                "Outros serviços"
            )
        );

        // Colocando os dados no XML para a requisição e salvando um arquivo XML com os dados
        // $layout = view('boleto.layout-requisicao', compact('data'));
        // Storage::disk('local')->put('requisicao.xml', $layout);
        // // Recuperando o token para a requisição
        // $token = json_decode(TokenController::gerar());

        // $ch = curl_init();
		// $options = array(
        //     CURLOPT_URL => $url,
		// 	CURLOPT_BINARYTRANSFER => true,
		// 	CURLOPT_RETURNTRANSFER => true,
		// 	CURLOPT_SSL_VERIFYPEER => false,
		// 	CURLOPT_SSL_VERIFYHOST => 0,
		// 	CURLOPT_FOLLOWLOCATION => true,
		// 	CURLOPT_POST => true,
        //     CURLOPT_MAXREDIRS => 3,
        //     CURLOPT_POSTFIELDS => Storage::disk('local')->get('requisicao.xml'),
        //     CURLOPT_HTTPHEADER => array(
        //         'Content-Type: text/xml;charset=UTF-8',
        //         "Authorization: Bearer $token->access_token",
        //         'SOAPAction: registrarBoleto'
        //     )
        // );
        
        // // $ch = curl_init();
        // curl_setopt_array($ch, $options);

        // $exe = curl_exec($ch);
        // curl_close($ch);
        // Storage::disk('local')->put('resposta.xml', $exe);

        $file = Storage::disk('local')->get('resposta.xml');

        $dataBoleto = $this->loadXmlBB($file); 
        // Retornando a informação em formato JSON
        $boleto = array(
            "nosso_numero" => $dataBoleto["ns0_textoNumeroTituloCobrancaBb"],
            "linha_digitavel" => $dataBoleto["ns0_linhaDigitavel"],
            "codigo_barras_numerico" => $dataBoleto["ns0_codigoBarraNumerico"],
            "erros" => $dataBoleto["ns0_textoMensagemErro"]
        );

        $infoBoleto = array_merge($data, $boleto);

        DB::table('boletos')->insert([
            [
                'convenio' => $infoBoleto["convenio"],
                'carteira' => $infoBoleto["carteira"],
                'variacaoCarteira' => $infoBoleto["variacao_carteira"],
                'dtEmissao' => Carbon::parse($infoBoleto["dt_emissao"])->format('Y-m-d'),
                'dtVencimento' => Carbon::parse($infoBoleto["dt_vencimento"])->format('Y-m-d'),
                'valorBoleto' => $infoBoleto["valor_boleto"],
                'tipoDesconto' => $infoBoleto["tipo_desconto"],
                'nomeCliente' => $infoBoleto["nome_cliente"],
                'cpfCnpj' => $infoBoleto["cpf_cnpj"],
                'rua' => $infoBoleto["endereco"]["rua"],
                'cidade' => $infoBoleto["endereco"]["cidade"],
                'estado' => $infoBoleto["endereco"]["estado"],
                'cep' => $infoBoleto["endereco"]["cep"],
                'demonstrativo' => join(" - ", $infoBoleto["demonstrativo"]),
                'nossoNumero' => $infoBoleto["nosso_numero"],
                'arquivo' => 'resposta.xml',
                'linhaDigitavel' => $infoBoleto["linha_digitavel"],
                'codigoBarrasNumerico' => $infoBoleto["codigo_barras_numerico"],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]
        ]);

        return json_encode($infoBoleto);
    }

    public function loadXmlBB($file){
         // O techo de código a seguir faz a leitura do XML e retorna a estrutura em array
         function mungXML($xml)
         {
             $obj = SimpleXML_Load_String($xml);
             if ($obj === FALSE) return $xml;
 
             $nss = $obj->getNamespaces(TRUE);
             if (empty($nss)) return $xml;
 
             $nsm = array_keys($nss);
             foreach ($nsm as $key)
             {
                 $rgx
                 = '#'
                 . '('
                 . '\<'
                 . '/?'
                 . preg_quote($key)
                 . ')'
                 . '('
                 . ':{1}'
                 . ')'
                 . '#'
                 ;
                 $rep
                 = '$1'
                 . '_'
                 ;
                 $xml =  preg_replace($rgx, $rep, $xml);
             }
             return $xml;
         }
 
         $XML = mungXML( trim($file) );
        return  $array = json_decode(json_encode(SimpleXML_Load_String($XML, 'SimpleXMLElement', LIBXML_NOCDATA)), true)['SOAP-ENV_Body']["ns0_resposta"];
    }

    public function gerarBoleto($nosso_numero){

        $data = DB::table('boletos')->select(
            "convenio",
            "carteira",
            "variacaoCarteira as variacao_carteira",
            "valorBoleto as valor_boleto",
            "nomeCliente as nome_cliente",
            "cpfCnpj as cpf_cnpj",
            "rua",
            "estado",
            "cidade",
            "demonstrativo"
        )
        ->where('nossoNumero', $nosso_numero)
        ->get()
        ->toArray();

        $res = json_decode(json_encode($data[0]), true);

        // return $res;
        
        // O arquivo será salvo com nosso número como parte do nome para recuperar
        $file = Storage::disk('local')->get('resposta.xml');

        $dataBoleto = $this->loadXmlBB($file);
        // Retornando a informação em formato JSON
        $boleto = array(
            "nosso_numero" => $dataBoleto["ns0_textoNumeroTituloCobrancaBb"],
            "linha_digitavel" => $dataBoleto["ns0_linhaDigitavel"],
            "codigo_barras_numerico" => $dataBoleto["ns0_codigoBarraNumerico"],
            "erros" => $dataBoleto["ns0_textoMensagemErro"]
        );

        // return $boleto;

        $req = array_merge($res, $boleto);

        // return $req;
        
        // ------------------------- DADOS DINÂMICOS DO SEU CLIENTE PARA A GERAÇÃO DO BOLETO (FIXO OU VIA POST) -------------------- //
        // DADOS DO BOLETO PARA O SEU CLIENTE
        $dias_de_prazo_para_pagamento = 5;
        // $taxa_boleto = 2.95;
        $taxa_boleto = 0;
        $data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 
        $valor_cobrado = $req["valor_boleto"]; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
        $valor_cobrado = str_replace(",", ".",$valor_cobrado);
        $valor_boleto = number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
        $dadosboleto["nosso_numero"] = $req["nosso_numero"];
        $dadosboleto["numero_documento"] = "19371176";	// Num do pedido ou do documento
        $dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
        $dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
        $dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
        $dadosboleto["valor_boleto"] = number_format($req["valor_boleto"], 2, ',', ''); 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
        $dadosboleto["linha_digitavel"] = $req['linha_digitavel']; //Linha digitável
        $dadosboleto["codigo_barras"] = $req['codigo_barras_numerico']; //Código de barras
        // DADOS DO SEU CLIENTE
        $dadosboleto["sacado"] = $req['nome_cliente'] ." - CNPJ/CPF: ". $req['cpf_cnpj'];
        $dadosboleto["endereco"] = array(
            $req['rua'],
            $req['cidade'] . " - " . $req['estado'] . (!empty($req['cep']) ? " - " . $req['cep'] : "")
        );
        // INFORMACOES PARA O CLIENTE
        $dadosboleto["demonstrativo"] = array(
            $req['demonstrativo']
        );
        // INSTRUÇÕES PARA O CAIXA
        $dadosboleto["instrucoes"] = array(
            "- Sr. Caixa, cobrar multa de 2% após o vencimento",
            "- Receber até 10 dias após o vencimento"
            // "- Em caso de dúvidas entre em contato conosco: xxxx@xxxx.com.br"
        );
        // DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
        // $dadosboleto["quantidade"] = "10";
        // $dadosboleto["valor_unitario"] = "10";
        // $dadosboleto["aceite"] = "N";		
        // $dadosboleto["especie"] = "R$";
        // $dadosboleto["especie_doc"] = "DM";
        
        $dadosboleto["quantidade"] = "10";
        $dadosboleto["valor_unitario"] = "1000";
        $dadosboleto["aceite"] = "N";		
        $dadosboleto["especie"] = "R$";
        $dadosboleto["especie_doc"] = "DM";

        // ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
        // DADOS DA SUA CONTA - BANCO DO BRASIL
        $dadosboleto["agencia"] = "3795"; // Num da agencia, sem digito
        $dadosboleto["conta"] = "4774"; 	// Num da conta, sem digito
        // DADOS PERSONALIZADOS - BANCO DO BRASIL
        $dadosboleto["convenio"] = $req["convenio"];  // Num do convênio - REGRA: 6 ou 7 ou 8 dígitos
        $dadosboleto["contrato"] = "19371176"; // Num do seu contrato
        $dadosboleto["carteira"] = $req["carteira"];
        $dadosboleto["variacao_carteira"] = $req["variacao_carteira"];  // Variação da Carteira, com traço (opcional)
        // TIPO DO BOLETO
        $dadosboleto["formatacao_convenio"] = "7"; // REGRA: 8 p/ Convênio c/ 8 dígitos, 7 p/ Convênio c/ 7 dígitos, ou 6 se Convênio c/ 6 dígitos
        $dadosboleto["formatacao_nosso_numero"] = "1"; // REGRA: Usado apenas p/ Convênio c/ 6 dígitos: informe 1 se for NossoNúmero de até 5 dígitos ou 2 para opção de até 17 dígitos
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

        echo view('pdf/layout-boleto/layout', compact('dadosboleto'));

        // return response(view('pdf/layout-boleto/layout', compact('dadosboleto')), 200)
        //           ->header('Content-Type', 'text/html');

        // return response()->json($req->all(), 200);

    }
}
