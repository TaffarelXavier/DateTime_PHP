<?php

class Contar_Datas {

    function getDiaDoAno($mes) {
        $timespam = mktime(0, 0, 0, $mes, 01, date("Y"));
        $arr = array();
        for ($dia = 1; $dia < date("t", $timespam) + 1; $dia++) {
            $arr[] = date("N d-m-Y", mktime(0, 0, 0, $mes, $dia, date("Y")));
        }
        return $arr;
    }

    /**
     * 
     * @param type $mes1 O primeiro mês 
     * @param type $mes2 O segundo mês
     * @param type $arr Um array
     * @return type
     */
    public function get_Dias_Uteis_do_Ano($mes1 = 1, $mes2 = 13, &$arr = array()) {

        function calback($n) {
            return preg_replace("/^(6|7)\s\d{1,2}\-\d{1,2}\-\d{4}/i", "-", $n);
        }

        for ($index = $mes1; $index < $mes2; $index++) {
            foreach (array_map('calback', $this->getDiaDoAno($index)) as $value) {
                if ($value != "-") {
                    $arr[] = preg_replace("/^\d\s/i", "", $value);
                }
            }
        }
        return $arr;
    }

}

class DataClass extends Contar_Datas {

    const EQUAL = "IGUAL";
    const DIFERENT = "DIFERENTE";
    const MAIOR = "MAIOR";
    const MENOR = "MENOR";
    const DATA_INEXISTENTE = "Data inexistente";
    const DATA_FORMAT_ERRADO = "O formato da data está errado";
    const DATA_ANO_INVALIDO = "O ano é inválido";
    const DATA_EMPTY = "A data está vazia.";

    private $data = array();

    public function __construct() {
        $key = func_get_args();
        $COUNT = func_num_args();
        if ($COUNT > 0) {
            $this->data[0] = $key[0];
            $this->data[1] = $key[1];
        }
    }

    public function setData() {
        $key = func_get_args();
        $this->data[0] = $key[0];
        $this->data[1] = $key[1];
    }

    public function getData() {
        return $this->data;
    }

    /**
     * @name <b>format_Data</b>
     * @author Taffarel Xavier <taffarel_deus@hotmail.com>
     * @link http://localhost/estudando-data/documentacao/class-DataClass.html Função muito massa de Data
     * <p>Função muito importante para trabalhar com datas</p>
     * @param String $data A data a qual a função usará.
     * @param Array $match A data saída como retorno. Exemplo: 2015-02-2
     * @example var_dump($DataClass->format_Data($data_l));
     * @return Array Retona um array com todos os paramentros da Data.
     * <br/>Dado a data: 2015-02-15, a saída será:<br/>
     * <p>data array (size=16)<br/>
      'year' => int 2015 O ano<br/>
      'month' => int 2 o mês<br/>
      'day' => int 15 o dia<br/>
      'hour' => boolean false a hora<br/>
      'minute' => boolean false O minuto<br/>
      'second' => boolean false O segundo<br/>
      'fraction' => boolean false A fração<br/>
      'warning_count' => int 0 Atenção<br/>
      'warnings' =><br/>
      array (size=0)<br/>
      empty<br/>
      'error_count' => int 0 Se há algum erro.<br/>
      'errors' =><br/>
      array (size=0)<br/>
      empty<br/>
      'is_localtime' => boolean false<br/>
      0 => string '2015-02-15' (length=10) A data como string<br/>
      'date_to_str' => string '2015-02-15' (length=10) A data como string <br/>
      1 => int 1423958400 O timespam da data<br/>
      'timespam_to_mes' => int 1423958400O timespam da data<br/></p>
     */
    public function format_Data($data, &$match = array(), $ano = 37658) {
        if (empty($data)) {
            return self::DATA_EMPTY;
        }
        $val_data = preg_match("/^\d{4}\-\d{1,2}\-\d{1,2}$/i", $data, $match);
        if ($val_data) {
            $exp = explode("-", $data);
            if ($exp[0] < 1900 || $exp[0] > $ano) {
                return self::DATA_ANO_INVALIDO;
            }
            if (checkdate($exp[1], $exp[2], $exp[0])) {
                $arr = date_parse($data);
                $arr[0] = $match[0];
                $arr["date_to_str"] = $match[0];
                $arr[1] = $this->get_Mktime($arr['month'], $arr['day'], $arr['year']);
                $arr["timestamp"] = $this->get_Mktime($arr['month'], $arr['day'], $arr['year']);
                $arr[] = $this->somarData(date("Y-m-d"), $match[0]);
                return $arr;
            } else {
                return self::DATA_INEXISTENTE;
            }
        } else {
            return array(self::DATA_FORMAT_ERRADO);
        }
    }

    /**
     * 
     * @param type $date_l A data inicial
     * @param type $date_r A data final
     * <p>
     * Dado as datas: 2015-02-25 & 2015-03-01 temos:<br/>
     * array (size=7)
      0 => string 'DIFERENTE' (length=9)<br/>
      'is_Equals' => boolean false<br/>
      'MAIOR_DATA' =>
      array (size=2)
      0 => int 1425168000
      1 => string '01-03-2015' (length=10)
      1 => string '25-02-2015' (length=10)
      2 => string '01-03-2015' (length=10)
      3 => int 1424822400
      4 => int 1425168000</p>
     * @return Array Retorna um array com todas as diferenças entre as duas datas.
     */
    public function igualdade_De_Datas($date_l, $date_r) {
        if ((int) $date_l == (int) $date_r) {
            return array(self::EQUAL,
                "isEquals" => TRUE,
                "MAIOR_DATA" => array(max($date_l, $date_r),
                    date("d-m-Y", max($date_l, $date_r))),
                $this->get_Data_From_get_Mktime($date_l),
                $this->get_Data_From_get_Mktime($date_r),
                $date_l,
                $date_r
            );
        } else {
            return array(self::DIFERENT,
                "isEquals" => FALSE,
                "MAIOR_DATA" => array(max($date_l, $date_r),
                    date("d-m-Y", max($date_l, $date_r))),
                $this->get_Data_From_get_Mktime($date_l),
                $this->get_Data_From_get_Mktime($date_r),
                $date_l,
                $date_r
            );
        }
    }

    /**
     * <p>
     * Outra forma de adicionar dia a uma data
     * Subtrai a minutos  a data atual</p>
     * @param type $menos_minutes
     * @return type
     */
    public function add_Days() {
        return date("d-m-Y", time() + (2 * 24 * 60 * 60));
    }

    /**
     * <p>Retorna o total de número de dias: 365 ou 366</p>
     * @return Inteiro Retorna 365 ou 366 se estiver em bixesto.
     */
    public function getNumeroDeAno(&$total = 0) {
        for ($x = 1; $x < 13; $x ++) {
            $total += date("t", mktime(0, 0, 0, $x, 01, date("Y")));
        }
        return (int) $total;
    }

    /**
     * <p>Retorna o total de número de dias: 365 ou 366</p>
     * @return Inteiro Retorna 365 ou 366 se estiver em bixesto.
     */

    /**
     * <p style='color:blue;font:16px arial;'>Soma a quantidade de dias dos meses.</p>
     * @param Inteiro $n1 Mes inicial Exemplo: 1 Pode está entre 1 e 12
     * @param Inteiro $n2 Mes Final Exemplo: 13 Pode está entre 1 e 13
     * @param Inteiro $total A quantidade final da somas dos números de dias.
     * @return Inteiro total de dias somados dos meses dados.
     * @example path <p>Dado: echo $DataClass->get_Calculo_De_Dias_Por_Mes(1,4); A saída será: 90 dias. <br/> É somado o primeiro, segundo e terceiro mês.</p>
     */
    public function get_Calculo_De_Dias_Por_Mes($n1 = 1, $n2 = 13, &$total = 0) {
        for ($x = $n1; $x < $n2; $x ++) {
            $total += date("t", mktime(0, 0, 0, $x, 01, date("Y")));
        }
        return (int) $total;
    }

    /**
     * <p>Verifica se uma data é valida ou não</p>
     * @param type $mes Mes
     * @param type $dia Dia
     * @param type $ano Ano
     * @return type boolean
     */
    public function check_Date_NP($mes, $dia, $ano) {
        return checkdate($mes, $dia, $ano);
    }

    /**
     * <p>Soma Duas Datas</p>
     * @param type $date1
     * @param type $date2
     * @return type
     */
    public function somarData($date1, $date2) {
        $d1 = new DateTime($date1);
        $d2 = new DateTime($date2);
        $intervalo = $d1->diff($d2);
        return array(
            "ANO" => $intervalo->y,
            "MES" => $intervalo->m,
            "DIA" => $intervalo->d,
            "HOR" => $intervalo->h,
            "MIN" => $intervalo->i,
            "SEG" => $intervalo->s,
            "invert" => $intervalo->invert,
            "DAYS" => $intervalo->days
        );
    }

    /**
     * <p>Verifica a igualdade de duas datas</p>
     * @param type $dateIni a Data inicial
     * @param type $dateFim A data Final
     * @return type boolean
     */
    public function dateIsEquals($dateIni, $dateFim) {
        return (bool) strtotime($dateIni) == strtotime($dateFim) ? true : false;
    }

    /**
     * <p>Adiciona dias a uma data</p>
     * @param type $data A data a receber a quantidade de dias
     * @param type $quantidade_dias A quantidade de dias
     * @return type
     */
    public function add_Days_To_Date($data, $quantidade_dias) {
//echo date('d/m/Y', strtotime($data . " + $quantidade_dias days"));
        return date('d-m-Y', strtotime("+$quantidade_dias days", strtotime($data)));
    }

    /**
     * <p>Adiciona a dias a data atual</p>
     * @param type $quantidade_dias
     * @return type
     */
    public function add_Day_To_Date($quantidade_dias) {
        return date("d-m-Y H:i:s", strtotime("+$quantidade_dias day"));
    }

    /**
     * <p>Adiciona a meses a data atual</p>
     * @param type $quant_meses
     * @return string Retorna uma data com o meses adiconado.
     */
    public function add_Month_To_Date($data, $quant_meses) {
        return date("Y-m-d", strtotime("+$quant_meses month", strtotime($data)));
    }

    /**
     * <p>Adiciona a minutos  a data atual</p>
     * @param type $mais_minutes
     * @return type
     */
    public function somar_Minutes_To_Date($hora, $mais_minutes) {
        return date('H:i:s', strtotime("+$mais_minutes minute", strtotime($hora)));
    }

    /**
     * <p>Subtrai a minutos  a data atual</p>
     * @param type $menos_minutes
     * @return type
     */
    public function subtrair_Minutes_To_Date($hora, $menos_minutes) {
        return date('H:i:s', strtotime("-$menos_minutes minute", strtotime($hora)));
    }

    /**
     * <p>Subtrai a minutos  a data atual</p>
     * @param type $menos_minutes
     * @return type
     */
    public function subtrair_Datas($data, $quant) {
        return date("Y-m-d", strtotime("-$quant month", strtotime($data)));
    }

    /**
     * <p>Analisa uma data e retorna o timestamp Unix</p>
     * @example path get_Mktime($mes, $dia, $ano) 
     * @param type $mes
     * @param type $dia
     * @param type $ano
     * @return type
     */
    public function get_Mktime($mes, $dia, $ano) {
        return mktime(0, 0, 0, (int) $mes, (int) $dia, (int) $ano);
    }

    /**
     * <p>Analisa uma data e retorna o timestamp Unix</p>
     * @param string $mes
     * @param string $dia
     * @param string $ano
     * @return type
     */
    public function get_Mk_From_Date() {
        return mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
    }

    /**
     * <p>Analisa um timestamp Unix e retorna a data</p>
     * @param int $timestamp o  timestamp Unix
     * @return String Retorna a data formatada
     */
    public function get_Data_From_get_Mktime($timestamp) {
        return date("d-m-Y", $timestamp);
    }

    public function is_Equals($data_ini, $data_fin) {
        if ($data_ini === $data_fin) {
            return self::EQUAL;
        } elseif ($data_ini > $data_fin) {
            return self::MAIOR;
        } elseif ($data_ini < $data_fin) {
            return self::MENOR;
        }
    }

    /**
     * <p>Obtem o número de dias em determinado mês.</p>
     * @param Integer $mes o mês atual
     * @return Integer Retona o número de dia em um mês.
     */
    public function getNumeroDeDiaNoMes($mes) {
        return date("t", mktime(0, 0, 0, $mes, 01, date("Y")));
    }

    /**
     * <p>Verifica se a data dada pelo usuário é maior ou igual da data  de hoje.</p>
     * @param type $data_final a Data do usuário. Um timespan
     * @return type boolean
     */
    public function date_IsEquals($data_final) {
        if ((int) $data_final == time()) {
            return self::EQUAL;
        } else if ((int) $data_final > time()) {
            return self::MAIOR;
        } else if ((int) $data_final < time()) {
            return self::MENOR;
        }
    }

}
