<?php

$css = " .content{ margin: 0 auto; max-width: 600px; text-align: center; padding-top: 15px } .area{ width: 100%; } .btn{ display: inline-block; padding: 6px 12px; margin-bottom: 0; font-size: 14px; font-weight: 400; line-height: 1.42857143; text-align: center; white-space: nowrap; vertical-align: middle; -ms-touch-action: manipulation; touch-action: manipulation; cursor: pointer; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background-image: none; border: 1px solid transparent; border-radius: 4px; margin-top: 15px; } .form-control { display: block; width: 100%; height: 34px; padding: 6px 12px; font-size: 14px; line-height: 1.42857143; color: #555; background-color: #fff; background-image: none; border: 1px solid #ccc; border-radius: 4px; -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075); box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075); -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s; -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s; transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s; } .form-control:focus { border-color: #66afe9; outline: 0; -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, .6); box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 175, 233, .6) } .form-control::-moz-placeholder { color: #999; opacity: 1 } .form-control:-ms-input-placeholder { color: #999 } .form-control::-webkit-input-placeholder { color: #999 } .form-control::-ms-expand { background-color: transparent; border: 0 } textarea.form-control { height: auto } .btn.active.focus, .btn.active:focus, .btn.focus, .btn:active.focus, .btn:active:focus, .btn:focus { outline: 5px auto -webkit-focus-ring-color; outline-offset: -2px } .btn.focus, .btn:focus, .btn:hover { color: #333; text-decoration: none } .btn.active, .btn:active { background-image: none; outline: 0; -webkit-box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125); box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125) } .btn-default { color: #333; background-color: #fff; border-color: #ccc } .btn-default.focus, .btn-default:focus { color: #333; background-color: #e6e6e6; border-color: #8c8c8c } .btn-default:hover { color: #333; background-color: #e6e6e6; border-color: #adadad } .btn-default.active, .btn-default:active, .open > .dropdown-toggle.btn-default { color: #333; background-color: #e6e6e6; border-color: #adadad } .btn-default.active.focus, .btn-default.active:focus, .btn-default.active:hover, .btn-default:active.focus, .btn-default:active:focus, .btn-default:active:hover, .open > .dropdown-toggle.btn-default.focus, .open > .dropdown-toggle.btn-default:focus, .open > .dropdown-toggle.btn-default:hover { color: #333; background-color: #d4d4d4; border-color: #8c8c8c } .btn-default.active, .btn-default:active, .open > .dropdown-toggle.btn-default { background-image: none } .btn-default.disabled.focus, .btn-default.disabled:focus, .btn-default.disabled:hover, .btn-default[disabled].focus, .btn-default[disabled]:focus, .btn-default[disabled]:hover, fieldset[disabled] .btn-default.focus, fieldset[disabled] .btn-default:focus, fieldset[disabled] .btn-default:hover { background-color: #fff; border-color: #ccc } .traitement { text-align: left; } ";

$head = <<< EOFILE
    <!DOCTYPE html> <html lang="en" xml:lang="en"> <head> <title>NelDev</title> <meta charset="utf-8"> <style type="text/css">${css}</style> </head> <body> <div class="content">
EOFILE;

$foot = <<< EOFILE
	<form method="post"> <textarea name="val" rows="5" id="t" class="form-control area" placeholder="Please, enter all the data here"></textarea> <button class="btn btn-default" type="submit">Valider</button> </form> </div> </body> </html>
EOFILE;




class Builder {

    private $data;
    private $trajets;
    private $nbrLign;
    private $nbrCol;
    private $nbrVoitur;
    private $nbrTraj;
    private $bonus;
    private $total;
    private $stat;

    public function __construct ($data) {
        $this->data = explode("\n", $data);

        $info = $this->data[0];
        $info = explode(' ', $info);

        $this->nbrLign = $info[0];
        $this->nbrCol = $info[1];
        $this->nbrVoitur = $info[2];
        $this->nbrTraj = $info[3];
        $this->bonus = $info[4];
        $this->total = $info[5];

        for($i=0; $i<$this->nbrVoitur; $i++){
            $this->stat[] = ['x' => 0, 'y' => 0, 'nbr' => 0, 'traj' => [], 'tt' => 0];
        }

        foreach($this->data as $k => $datum){
            if($k > 0 and $k != count($this->data)){
                $this->trajets[] = explode(' ', $datum);
            }
        }
        for($i = 0; $i < count($this->trajets); $i++){
            array_push($this->trajets[$i], ['num' => $i]);
        }
    }

    /**
     * Pour afficher la valeur qu'on a reçu pour palier aux éventuelles erreurs
     *
     * @return string
     */
    public function input(): string {
        $input = '';
        foreach ($this->data as $row) {
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
        $answer = [];
        $this->sorter();


        for($i=0; $i<$this->total; $i++){
            $nt = 0;
            $pret = [];
            foreach($this->trajets as $trajet){
                if($trajet[4] <= $i) {
                    $n = 0;
                    $pos = [];
                    $pre = true;
                    foreach($this->stat as $voiture){
                        if($voiture['x'] != $trajet[0]){
                            $pre = false;
                        }
                        if($voiture['y'] != $trajet[1]){
                            $pre = false;
                        }
                        if($pre){
                            $pos[] = ['num' => $n, 'p' => true, 'd' => 0, 'tr' => $nt];
                        }else{
                            $absx = abs($voiture['x'] - $trajet[0]);
                            $absy = abs($voiture['y'] - $trajet[1]);
                            $d = $absx + $absy;
                            $pos[] = ['num' => $n, 'p' => false, 'd' => $d, 'tr' => $nt];
                        }
                        $n++;
                    }


                    $keeep = null;
                    for($k = 0; $k < count($pos); $k++){
                        for($f = 0; $f < count($pos); $f++){
                            if($pos[$f]['p']){
                                $pret[] = $pos[$f];
                            }
                        }

                    }

                    for($k = 0; $k < count($pos)-1; $k++){
                        for($f = 0; $f < count($pos)-1; $f++){
                            if(!$pos[$f]['p']){
                                if($pos[$f]['d'] > $pos[$f + 1]['d']){
                                    $keeep = $pos[$f];
                                    $pos[$f] = $pos[$f + 1];
                                    $pos[$f + 1] = $keeep;
                                }
                            }
                        }
                    }


                    for($k = 0; $k < count($pos); $k++){
                        for($f = 0; $f < count($pos); $f++){
                            if(!$pos[$f]['p']){
                                $pret[] = $pos[$f];
                            }
                        }
                    }
                }
                $nt++;
            }
            foreach($pret as $value){
                if($value['p']){
                    $this->stat[$value['num']]['x'] = $this->trajets[$value['tr']][2];
                    $this->stat[$value['num']]['y'] = $this->trajets[$value['tr']][3];
                    $this->stat[$value['num']]['nbr'] = $this->stat[$value['num']]['nbr']+1;
                    $this->stat[$value['num']]['traj'][] = $value['tr'];


                    break;
                }
            }



        }
        foreach($this->stat as $k => $item){
            echo $k . ' ' . $item['nbr'];
            foreach($item['traj'] as $ite){
                echo $ite[0] . ' ';
            }
            echo '<br>';
        }

        return $answer;
    }

    public function sorter() {
        $keep = [];
        for($i = 0; $i < count($this->trajets)-1; $i++){
            for($j=0; $j < count($this->trajets)-1; $j++){
                if(abs($this->trajets[$j][0] - $this->trajets[$j][1]) > abs($this->trajets[$j+1][0] - $this->trajets[$j+1][1])){
                    $keep = $this->trajets[$j];
                    $this->trajets[$j] = $this->trajets[$j+1];
                    $this->trajets[$j+1] = $keep;
                }
            }
        }
    }



    /**
     * Transforme le tableau de processing() en un string
     *
     * @return string
     */
    public function output(): string {
        $html = "
        
        ";
        $t = $this->processing();
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