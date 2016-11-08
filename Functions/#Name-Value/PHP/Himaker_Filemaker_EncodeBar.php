<?php

/**
 * Convert Json code into #Name-Value Code
 *  
 * Copyright (c) 2016 Himaker
 *
 * @Recigio Poffo - recigio@himaker.com.br
 * www.himaker.com.br
 * 
 * @See License.txt
 *
 * Class Himaker_Filemaker_DecoderToBar
 */
class Himaker_Filemaker_EncodeBar {

    private $_source = "";
    private $_arrayData;
    private $_currentLevel;
    private $_lastLevel = 999999;

    /**
     * 
     * @param string $source
     * @return string
     * @throws Zend_Json_Exception
     */
    public static function encode($source = null)
    {
        $decoder = new Himaker_Filemaker_EncodeBar($source);
        return $decoder->parseToBar();
    }

    /**
     * Himaker_Filemaker_DecoderToBar constructor.
     * @param $source json
     */
    public function __construct($source)
    {
        $this->_source = $source;
        $this->_currentLevel = -1;
    }

    /**
     * Main call to parser
     * @return mixed
     */
    public function parseToBar(){

        $this->_arrayData = json_decode($this->_source, true);
        $data = $this->parseItem(null,$this->_arrayData)." ;";

        return $data;

    }

    /**
     * Parser item
     * @return mixed
     */
    public function parseItem($key, $item){

        $data = "";

        //choose correct parser
        if(is_array($item)){
            $data .= $this->parseArray($key,$item);
        } else {
            $data .= $this->parseValue($key,$item);
        }

        return $data;

    }

    /**
     * Parser value
     * @return mixed
     */
    public function parseValue($key, $item){

        //verify level to write correct syntax 
        $extra ='';
        if($this->_currentLevel == $this->_lastLevel)
            $extra .=  $this->writeBars($this->_currentLevel-1).'"';

        //verify array type to writte correct
        if(!is_int($key)){
           return $extra.'$'.$key.' = '.$this->writeBars($this->_currentLevel).'"'.$item.$this->writeBars($this->_currentLevel).'";'.$this->writeBars($this->_currentLevel-1).'¶'.'';
        } else {
            return $extra.$item.$this->writeBars($this->_currentLevel).'"'.$this->writeBars($this->_currentLevel-1).'¶'.'';

        }
    }

    /**
     * Parser array
     * @return mixed
     */
    public function parseArray($key, $array)
    {

        //add one deep level
        $this->_currentLevel++;
        $data = "";

        //verify array type and position
        if (!is_int($key) && $this->_currentLevel > 0){

            if ($this->_currentLevel ==1 )
                $data .= '$' . $key . ' = "' . $this->writeBars($this->_currentLevel) . '"'.'';
            else
                $data .= '$' . $key . ' = '.$this->writeBars($this->_currentLevel-1).'"' . $this->writeBars($this->_currentLevel) . '"'.'';

        }

        //send child's to parser
        foreach ($array as $keyChild => $itemChild ) {
            $data .= $this->parseItem($keyChild,$itemChild);
        }

        //save last deep level
        $this->_lastLevel = $this->_currentLevel;

        //remove emove one deep level
        $this->_currentLevel--;

        //write close command's
        if ($this->_currentLevel > 1){

            if(!is_int($key))
                $data .= $this->writeBars($this->_currentLevel).'" ;' . $this->writeBars($this->_currentLevel-1) . '¶'.'';
            else
                $data .= $this->writeBars($this->_currentLevel).'"' . $this->writeBars($this->_currentLevel-1) . '¶'.'';
        }

        //final instructions
        if ($this->_currentLevel == 0){
            $data .= $this->writeBars($this->_currentLevel).'"¶"';
        }

        return $data;
    }

    /**
     * Write \'s
     *
     * @param $level
     * @return string
     */
    public function writeBars($level){

        $barras = pow(2,$level);

        if($barras > 1)
            $barras = $barras-1;

        $stringbarras ='';
        for($i=0; $i<$barras; $i++){
            $stringbarras .='\\';
        }

        return $stringbarras;

    }


}
