<?php

$css = " .content{ margin: 0 auto; max-width: 600px; text-align: center; padding-top: 15px } .area{ width: 100%; } .btn{ display: inline-block; padding: 6px 12px; margin-bottom: 0; font-size: 14px; font-weight: 400; line-height: 1.42857143; text-align: center; white-space: nowrap; vertical-align: middle; -ms-touch-action: manipulation; touch-action: manipulation; cursor: pointer; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background-image: none; border: 1px solid transparent; border-radius: 4px; margin-top: 15px; } .form-control { display: block; width: 100%; height: 34px; padding: 6px 12px; font-size: 14px; line-height: 1.42857143; color: #555; background-color: #fff; background-image: none; border: 1px solid #ccc; border-radius: 4px; -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075); box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075); -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s; -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s; transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s; } .form-control:focus { border-color: #66afe9; outline: 0; -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, .6); box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, .6) } .form-control::-moz-placeholder { color: #999; opacity: 1 } .form-control:-ms-input-placeholder { color: #999 } .form-control::-webkit-input-placeholder { color: #999 } .form-control::-ms-expand { background-color: transparent; border: 0 } textarea.form-control { height: auto } .btn.active.focus, .btn.active:focus, .btn.focus, .btn:active.focus, .btn:active:focus, .btn:focus { outline: 5px auto -webkit-focus-ring-color; outline-offset: -2px } .btn.focus, .btn:focus, .btn:hover { color: #333; text-decoration: none } .btn.active, .btn:active { background-image: none; outline: 0; -webkit-box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125); box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125) } .btn-default { color: #333; background-color: #fff; border-color: #ccc } .btn-default.focus, .btn-default:focus { color: #333; background-color: #e6e6e6; border-color: #8c8c8c } .btn-default:hover { color: #333; background-color: #e6e6e6; border-color: #adadad } .btn-default.active, .btn-default:active, .open > .dropdown-toggle.btn-default { color: #333; background-color: #e6e6e6; border-color: #adadad } .btn-default.active.focus, .btn-default.active:focus, .btn-default.active:hover, .btn-default:active.focus, .btn-default:active:focus, .btn-default:active:hover, .open > .dropdown-toggle.btn-default.focus, .open > .dropdown-toggle.btn-default:focus, .open > .dropdown-toggle.btn-default:hover { color: #333; background-color: #d4d4d4; border-color: #8c8c8c } .btn-default.active, .btn-default:active, .open > .dropdown-toggle.btn-default { background-image: none } .btn-default.disabled.focus, .btn-default.disabled:focus, .btn-default.disabled:hover, .btn-default[disabled].focus, .btn-default[disabled]:focus, .btn-default[disabled]:hover, fieldset[disabled] .btn-default.focus, fieldset[disabled] .btn-default:focus, fieldset[disabled] .btn-default:hover { background-color: #fff; border-color: #ccc } .traitement { text-align: left; } ";

$head = <<< EOFILE
    <!DOCTYPE html> <html lang="en" xml:lang="en"> <head> <title>NelDev</title> <meta charset="utf-8"> <style type="text/css">${css}</style> </head> <body> <div class="content">
EOFILE;

$foot = <<< EOFILE
	<form method="post"> <textarea name="val" rows="5" id="t" class="form-control area" placeholder="Please, enter all the data here"></textarea> <button class="btn btn-default" type="submit">Valider</button> </form> </div> </body> </html>
EOFILE;

function dd($val){
    var_dump($val);
    die();
}


class Builder {

    private $data;
    private $cells;
    private $R;
    private $C;
    private $L;
    private $H;
    private $slices;

    public function __construct($data) {
        $this->data = explode("\n", $data);
        $first = explode(' ', $this->data[0]);
        $this->R = $first[0];
        $this->C = $first[1];
        $this->L = $first[2];
        $this->H = $first[3];
    }

    /**
     * Pour afficher la valeur qu'on a reçu pour palier aux éventuelles erreurs
     *
     * @return string
     */
    public function input(): string {
        $input = '';
        $n = 0;
        foreach($this->data as $row){
            $n++;
            if($n > 1){
                $line = [];
                for($i = 0; $i < strlen($row); $i++){
                    if($row[$i] == 'T' || $row[$i] == 'M')$line[] = $row[$i];
                }
                $this->cells[] = $line;
            }
            $input .= $row;
            $input .= '<br>';
        }
        return $input;
    }

    /**
     * Traitement de l'input pour retourner le tableau à afficher à l'aide de HTML
     *
     * @return array
     */
    public function processing(): array {
        $nbrT = $this->nombreDe('T');
        $nbrM = $this->nombreDe('M');

        if($nbrT < $nbrM) {
            $this->slices = intval($nbrT / $this->L);
        }else{
            $this->slices = intval($nbrM / $this->L);
        }
        var_dump('On aura donc ' . $this->slices . ' slices');

        return [];
    }

    private function nombreDe($lettre) {
        $n = 0;
        foreach($this->cells as $cell) {
            foreach($cell as $item){
                if($item === $lettre) $n++;
            }
        }
        return $n;
    }




    /**
     * Transforme le tableau de processing() en un string
     *
     * @return string
     */
    public function output(): string {
        $data = $this->processing();

        $html = "
        
        ";

        return $html;
    }

}

if(isset($_POST['val'])){
    $builder = new Builder(strip_tags($_POST['val']));

    $input = $builder->input();
    $output = $builder->output();

    $pageContents = <<< EOFILE
        ${head}
    		<div class="traitement">
    		    <h2>Input :</h2>
                ${input}
                <h2>Output :</h2>
                ${output}
           </div>
		${foot}
EOFILE;
}else{
    $pageContents = <<< EOFILE
        ${head}
		${foot}
EOFILE;
}


echo $pageContents;