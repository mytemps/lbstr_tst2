<?php
class App
{

    public $statistics_mean = 200; // ожидаем игроков всего, от и до (или из статистики)
    public $max_days = 7; // дней всего длится
    public $hardcode_prob = null; // дней всего длится


    /**
     * @throws Exception
     */
    public function __construct() {

    }

    /**
     * @return array
     * @throws Exception
     */
    public function startEmulation(): array
    {

        // данные по дням
        $STAT = [];

        $_day = 1;

        // // каждый день проходим

        while($_day <= $this->max_days) {
            $STAT[$_day] = [];

            // ожидаем сегодня
            if($_day==1){
                // среднее исходя из статистики
                $STAT[$_day]['expecting'] = $this->statistics_mean;
            }else{
                // ожадали-получили среднее за вчера
                $STAT[$_day]['expecting'] = round(($STAT[$_day-1]['count']+$STAT[$_day-1]['expecting']) / 2);
            }

            // вероятность
            $STAT[$_day]['probability'] = $this->hardcode_prob?:1/$STAT[$_day]['expecting'];


            // // игроки пошли, пока не кончится день
            // // без победителя день не кончается

            $_stop = false; // тормозим ("кончился день")
            $_winner = 0; // победитель
            $_num = 1; // текущий игрок (дня)

            while(!$_stop) {

                if(!$_winner) {

                    // проверяем победитель ли с учетом вероятности на сегодня
                    // просто 1/N равно вероятность по сути, чтобы можно было через
                    // через переменную на результат влиять
                    if(!$this->hardcode_prob) {
                        $this_is_winner = 1 / rand(1, $STAT[$_day]['expecting']) == $STAT[$_day]['probability'];
                    }else{
                        $this_is_winner = rand(1,100) <= $this->hardcode_prob*100;
                    }
                    if ($this_is_winner) {
                        $STAT[$_day]['winner'] = $_winner = $_num;
                    }

                }

                // ДЛЯ ЭМУЛЯЦИИ
                // стопим рандомно, но только если есть победитель уже
                if($_winner){
                    if($STAT[$_day]['expecting']>$STAT[$_day]['winner']){
                        $_num+=rand($STAT[$_day]['winner'],$STAT[$_day]['expecting']);
                    }
                    $_stop = true;
                }else{
                    $_num++;
                }


            }

            // // кончился день

            $STAT[$_day]['count'] = $_num;

            $_day++;
        }


        // //


        $count_min = min(array_column($STAT,'count'));
        $count_max = max(array_column($STAT,'count'));

        $winner_min = min(array_column($STAT,'winner'));
        $winner_max = max(array_column($STAT,'winner'));

        $prob_min = min(array_column($STAT,'probability'));
        $prob_max = max(array_column($STAT,'probability'));

        $ALL = [
            'days'=>count($STAT),
            'count_summ'=>array_sum(array_column($STAT,'count')),

            'COUNT_min'=>$count_min,
            'COUNT_max'=>$count_max,
            'COUNT_mean'=>round(($count_min+$count_max)/2),

            'WINNER_min'=>$winner_min,
            'WINNER_max'=>$winner_max,
            'WINNER_mean'=>round(($winner_min+$winner_max)/2),

            'PROB_min'=>$prob_min,
            'PROB_max'=>$prob_max,
            'PROB_mean'=>($prob_min+$prob_max)/2
        ];

        return ['stat'=>$STAT,'all'=>$ALL];
    }

}

