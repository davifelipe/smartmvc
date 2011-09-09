<?php
/**
 * select locales
 * @version 1.0
 * @copyright smartmvc.com.br
 * @author Davi Felipe <davifelipems@hotmail.com>
 */

class SMVC_Locale{

    /**
     * stores array with all locales content
     * @var <Array>
     */
    private $localesContent;

    public function __construct() {

        $this->localesContent =array('pt_BR'=>
                                            array(0=>'Locale não encontrado',
                                                  1=>'Arquivo de configuracao nao encontrado: ',
                                                  2=>'Diretorio de módulos nao encontrado:',
                                                  3=>'Deretorio de templates nao encontrado:',
                                                  4=>'arquivo de template nao encontrado ',
                                                  5=>'Erro ao carregar template ',
                                                  6=>'Verifique se o arquivo de template existe em ',
                                                  7=>'Não foi possível conectar a base de dados!',
                                                  8=>'Não foi posssível acessar o banco de dados!',
                                                  9=>'Erro ao executar consulta: ',
                                                  10=> 'cláusula limit inválida: ',
                                                  11=>'cláusula order inválida: ',
                                                  12=>'Erro ao carregar classe ',
                                                  13=>'O controller deve ser subclasse de SMVC_ControllerAbsctract',

                                                  14=>'Dados salvos com sucesso!',
                                                  15=>'O valor deve ser numérico',
                                                  16=>'O valor não pode ser vazio',

                                                 ),
                                      'en_US'=>
                                             array(0=>'Locale not found',
                                                  1=>'Configuration file not found: ',
                                                  2=>'Directory of modules not found: ',
                                                  3=>'Template directory not found: ',
                                                  4=>'template file not found ',
                                                  5=>'Error loading template ',
                                                  6=>'Make sure the template file exists in',
                                                  7=>'Could not connect to database! ',
                                                  8=>'Could not select database! ',
                                                  9=>'Error executing query: ',
                                                  10=> 'limit clause invalid: ',
                                                  11=>'limit order invalid: ',
                                                  12=>'Error loading class',
                                                  13=>'The controller should extends of SMVC_ControllerAbsctract',

                                                  14=>'Save success!',
                                                  15=>'the value should te numeric',
                                                  16=>'the value shoul not be null',
                                                 
                                                 )
        );

    }

    public static function setLocale($locale){
		
        
        $localeObj = new SMVC_Locale();
        
        foreach ($localeObj->localesContent as $loc => $content) {
            ($locale == $loc ? $_SESSION['_currentLocale'] = $locale : '');
        }
		
    }

    public static function getContent($cod){
		
        $locale = new SMVC_Locale();

        if(!$_SESSION['_currentLocale'])
            throw new SMVC_Exception_Start($locale->localesContent['en_Us'][0]);

        return $locale->localesContent[$_SESSION['_currentLocale']][$cod];

    }

}
