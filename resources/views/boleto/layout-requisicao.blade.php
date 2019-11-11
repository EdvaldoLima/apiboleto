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
 <sch:valorOriginalTitulo>{{$data['valor']}}</sch:valorOriginalTitulo>
 <sch:codigoTipoDesconto>{{$data['tipo_desconto']}}</sch:codigoTipoDesconto>
 <sch:codigoAceiteTitulo>N</sch:codigoAceiteTitulo>
 <sch:codigoTipoTitulo>2</sch:codigoTipoTitulo>
 <sch:textoDescricaoTipoTitulo>DUPLICATA</sch:textoDescricaoTipoTitulo>
 <sch:indicadorPermissaoRecebimentoParcial>N</sch:indicadorPermissaoRecebimentoParcial>
 <sch:textoNumeroTituloBeneficiario>987654321987654</sch:textoNumeroTituloBeneficiario>
 <sch:textoCampoUtilizacaoBeneficiario/>
 <sch:codigoTipoContaCaucao>1</sch:codigoTipoContaCaucao>
 <sch:textoNumeroTituloCliente>00010140510000000000</sch:textoNumeroTituloCliente>
 <sch:textoMensagemBloquetoOcorrencia>Pagamento disponível até a data devencimento</sch:textoMensagemBloquetoOcorrencia>
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
</soapenv:Envelope>