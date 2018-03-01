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

    public function __construct($data) {

        $this->data = explode("\n", $data);
    }

    /**
     * Pour afficher la valeur qu'on a reçu pour palier aux éventuelles erreurs
     *
     * @return string
     */
    public function input(): string {

        $input = '';
        foreach($this->data as $row){
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

        $rows = $this->data;
        $cas = 0;
        $answer = [];
        $nbrEq = 0;
        $answered = [];
        foreach($rows as $row){
            $parts = explode(' ', $row);
            $geted = false;
            if(count($parts) == 1){
                $old = $nbrEq;
                $nbrEq = $parts[0];
                if($nbrEq == 0){
                    if($cas > 0){
                        $answer = $this->sorter($answer);
                        array_push($answered, ['cas' => $cas, 'nbrEq' => $old, 'answer' => $answer]);
                        $answer = [];
                    }
                    continue;
                }else{
                    $cas++;
                }
                $newCase = true;
            }else{
                $newCase = false;
            }
            if($newCase){
                continue;
            }else{
                foreach($answer as $ans){
                    $b = $ans['numEq'] == $parts[1];
                    if($b){
                        $answer = $this->newRun($answer, $parts);
                        $geted = true;
                    }
                }
                if(!$geted){
                    $substr = substr($parts[3], 0, 3);
                    $juge = $substr == 'oui';
                    if($juge){
                        array_push($answer, ['numEq' => $parts[1], 'time' => $parts[0], 'nbr' => 1]);
                    }else{
                        array_push($answer, ['numEq' => $parts[1], 'time' => 20, 'nbr' => 0]);
                    }
                }
            }
        }

        return $answered;
    }


    public function newRun($answer, $parts) {

        for($i = 0; $i < count($answer); $i++){
            if($answer[$i]['numEq'] == $parts[1]){
                if(substr($parts[3], 0, 3) === 'oui'){
                    $answer[$i]['time'] += $parts[0];
                    $answer[$i]['nbr']++;
                }
            }
        }

        return $answer;
    }

    public function sorter($ans) {

        $keep = [];

        for($j = 0; $j < count($ans) - 1; $j++){
            for($i = 0; $i < count($ans) - 1; $i++){
                if($ans[$i]['nbr'] * 1 < $ans[$i + 1]['nbr'] * 1){
                    $keep = $ans[$i + 1];
                    $ans[$i + 1] = $ans[$i];
                    $ans[$i] = $keep;
                }elseif($ans[$i]['nbr'] * 1 == $ans[$i + 1]['nbr'] * 1){
                    if($ans[$i]['time'] * 1 > $ans[$i + 1]['time'] * 1){
                        $keep = $ans[$i + 1];
                        $ans[$i + 1] = $ans[$i];
                        $ans[$i] = $keep;

                        if($ans[$i]['numEq'] * 1 < $ans[$i + 1]['numEq'] * 1){
                            $keep = $ans[$i + 1];
                            $ans[$i + 1] = $ans[$i];
                            $ans[$i] = $keep;
                        }
                    }
                }
            }
        }

        return $ans;
    }

    /**
     * Transforme le tableau de processing() en un string
     *
     * @return string
     */
    public function output(): string {

        $data = $this->processing();
        $cas = 0;
        //var_dump($data);
        $html = "
        <table style='width: 100%; text-align: center; margin-bottom: 20px' border='1'>
            <tr>
                <td>Num Eq</td>
                <td>Rang</td>
                <td>Nbr Pr</td>
                <td>Temps Total</td>
            </tr>
        ";
        foreach($data as $item){
            if($item['nbrEq'] > 0){
                $cas++;
                $html .= "
                    <tr style='border: none;'>
                        <td style='padding-top: 10px' colspan='4'>Cas ${cas}</td>
                    </tr>
                ";
            }
            if(count($item['answer']) > 0){
                $r = 0;
                $oldNbr = 0;
                for($i = 0; $i < $item['nbrEq']; $i++){
                    if(isset($item['answer'][$i])){
                        $answer = $item['answer'][$i];
                        if(!$r){
                            $r = $i + 1;
                        }else{
                            if($answer['time'] != $oldNbr) $r = $i+1;
                        }
                        $oldNbr = $answer['time'];
                        $html .= "
                            <tr>
                                <td>${answer['numEq']}</td>
                                <td>${r}</td>
                                <td>${answer['nbr']}</td>
                                <td>${answer['time']}</td>
                            </tr>
                        ";
                    }
                }
            }else{
                for($i = 0; $i < $item['nbrEq']; $i++){
                    $r = $i + 1;
                    $html .= "
                            <tr>
                                <td>${r}</td>
                                <td>1</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                    ";
                }
            }
        }

        $html .= "
        </table>
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