<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
xmlns:sch="http://www.tibco.com/schemas/bws_registro_cbr/Recursos/XSD/Schema.xsd">
 <soapenv:Header/>
 <soapenv:Body>
 <sch:requisicao>
 <sch:numeroConvenio>{{$data['convenio']}}</sch:numeroConvenio>
 <sch:numeroCarteira>{{$data['carteira']}}</sch:numeroCarteira>
 <sch:numeroVariacaoCarteira>{{$data['variacao_carteira']}}</sch:numeroVariacaoCarteira>
 <sch:codigoModalidadeTitulo>{{$data['modalidade']}}</sch:codigoModalidadeTitulo>
 <sch:dataEmissaoTitulo>{{$data['dt_emissao']}}</sch:dataEmissaoTitulo>
 <sch:dataVencimentoTitulo>{{$data['dt_vencimento']}}</sch:dataVencimentoTitulo>
 <sch:valorOriginalTitulo>{{$data['valor_boleto']}}</sch:valorOriginalTitulo>
 <!-- <sch:codigoTipoDesconto>{{$data['tipo_desconto']}}</sch:codigoTipoDesconto> -->
 <sch:codigoAceiteTitulo>N</sch:codigoAceiteTitulo>
 <sch:codigoTipoTitulo>2</sch:codigoTipoTitulo>
 <!-- <sch:textoDescricaoTipoTitulo>DUPLICATA</sch:textoDescricaoTipoTitulo> -->
 <sch:indicadorPermissaoRecebimentoParcial>N</sch:indicadorPermissaoRecebimentoParcial>
 <!-- <sch:textoNumeroTituloBeneficiario>987654321987654</sch:textoNumeroTituloBeneficiario> -->
 <sch:textoCampoUtilizacaoBeneficiario/>
 <sch:codigoTipoContaCaucao>1</sch:codigoTipoContaCaucao>
 <sch:textoNumeroTituloCliente>{{$data['titulo_cliente']}}</sch:textoNumeroTituloCliente>
 <sch:textoMensagemBloquetoOcorrencia>Pagamento disponível até a data devencimento</sch:textoMensagemBloquetoOcorrencia>
 <sch:codigoTipoInscricaoPagador>2</sch:codigoTipoInscricaoPagador>
 <sch:numeroInscricaoPagador>73400584000166</sch:numeroInscricaoPagador>
 <sch:nomePagador>{{$data['nome_cliente']}}</sch:nomePagador>
 <sch:textoEnderecoPagador>{{$data['endereco']['rua']}}</sch:textoEnderecoPagador>
 <!-- <sch:numeroCepPagador>12345678</sch:numeroCepPagador> -->
 <sch:nomeMunicipioPagador>{{$data['endereco']['cidade']}}</sch:nomeMunicipioPagador>
 <sch:nomeBairroPagador>SIA</sch:nomeBairroPagador>
 <sch:siglaUfPagador>{{$data['endereco']['estado']}}</sch:siglaUfPagador>
 <!-- <sch:textoNumeroTelefonePagador>45619988</sch:textoNumeroTelefonePagador> -->
 <sch:codigoTipoInscricaoAvalista/>
 <sch:numeroInscricaoAvalista/>
 <sch:nomeAvalistaTitulo/>
 <sch:codigoChaveUsuario>1</sch:codigoChaveUsuario>
 <sch:codigoTipoCanalSolicitacao>5</sch:codigoTipoCanalSolicitacao>
 </sch:requisicao>
 </soapenv:Body>
</soapenv:Envelope>

<!-- <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
xmlns:sch="http://www.tibco.com/schemas/bws_registro_cbr/Recursos/XSD/Schema.xsd">
<soapenv:Header/>
<soapenv:Body>
<sch:requisicao>
<sch:numeroConvenio>{{$data['convenio']}}</sch:numeroConvenio>
<sch:numeroCarteira>{{$data['carteira']}}</sch:numeroCarteira>
<sch:numeroVariacaoCarteira>{{$data['variacao_carteira']}}</sch:numeroVariacaoCarteira>
<sch:codigoModalidadeTitulo>1</sch:codigoModalidadeTitulo>
<sch:dataEmissaoTitulo>19.02.2020</sch:dataEmissaoTitulo>
<sch:dataVencimentoTitulo>21.02.2020</sch:dataVencimentoTitulo>
<sch:valorOriginalTitulo>30000</sch:valorOriginalTitulo>
<sch:codigoTipoDesconto>1</sch:codigoTipoDesconto>
<sch:dataDescontoTitulo>21.11.2016</sch:dataDescontoTitulo>
<sch:percentualDescontoTitulo/>
<sch:valorDescontoTitulo>10</sch:valorDescontoTitulo>
<sch:valorAbatimentoTitulo/>
<sch:quantidadeDiaProtesto>0</sch:quantidadeDiaProtesto>
<sch:codigoTipoJuroMora>0</sch:codigoTipoJuroMora>
<sch:percentualJuroMoraTitulo/>
<sch:valorJuroMoraTitulo/>
<sch:codigoTipoMulta>2</sch:codigoTipoMulta>
<sch:dataMultaTitulo>22.02.2020</sch:dataMultaTitulo>
<sch:percentualMultaTitulo>10</sch:percentualMultaTitulo>
<sch:valorMultaTitulo/>
<sch:codigoAceiteTitulo>N</sch:codigoAceiteTitulo>
<sch:codigoTipoTitulo>2</sch:codigoTipoTitulo>
<sch:textoDescricaoTipoTitulo>DUPLICATA</sch:textoDescricaoTipoTitulo>
<sch:indicadorPermissaoRecebimentoParcial>N</sch:indicadorPermissaoRecebimentoParcial>
<sch:textoNumeroTituloBeneficiario>987654321987654</sch:textoNumeroTituloBeneficiario>
<sch:textoCampoUtilizacaoBeneficiario/>
<sch:codigoTipoContaCaucao>1</sch:codigoTipoContaCaucao>
<sch:textoNumeroTituloCliente>00028057740000000004</sch:textoNumeroTituloCliente>
<sch:textoMensagemBloquetoOcorrencia>Pagamento disponível até a data de
vencimento</sch:textoMensagemBloquetoOcorrencia>
<sch:codigoTipoInscricaoPagador>2</sch:codigoTipoInscricaoPagador>
<sch:numeroInscricaoPagador>73400584000166</sch:numeroInscricaoPagador>
<sch:nomePagador>MERCADO ANDREAZA DE MACEDO</sch:nomePagador>
<sch:textoEnderecoPagador>RUA SEM NOME</sch:textoEnderecoPagador>
<sch:numeroCepPagador>12345678</sch:numeroCepPagador>
<sch:nomeMunicipioPagador>BRASILIA</sch:nomeMunicipioPagador>
<sch:nomeBairroPagador>SIA</sch:nomeBairroPagador>
<sch:siglaUfPagador>DF</sch:siglaUfPagador>
<sch:textoNumeroTelefonePagador>45619988</sch:textoNumeroTelefonePagador>
<sch:codigoTipoInscricaoAvalista/>
<sch:numeroInscricaoAvalista/>
<sch:nomeAvalistaTitulo/>
<sch:codigoChaveUsuario>1</sch:codigoChaveUsuario>
<sch:codigoTipoCanalSolicitacao>5</sch:codigoTipoCanalSolicitacao>
</sch:requisicao>
</soapenv:Body>
</soapenv:Envelope> -->