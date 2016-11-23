<?php use SaltedHerring\Debugger as Debugger;

class Vector extends DataObject {
    protected static $has_one = array(
        'Gesture'       =>  'Gesture'
    );

    protected static $has_many = array(
        'Points'        =>  'Point'
    );

    protected static $summary_fields = array(
        'Doodle'        =>  'Gesture'
    );

    public function format() {
        return $this->Points()->format();
    }

    public function getDimension()
    {
        $points = $this->Points();
        if ($points->count() == 0) {
            return array(
                'x'         =>  0,
                'y'         =>  0,
                'width'     =>  0,
                'height'    =>  0
            );
        }

        $dimension = array(
            'x'         =>  $points[0]->x,
            'y'         =>  $points[0]->y,
            'width'     =>  $points[0]->x,
            'height'    =>  $points[0]->y
        );

        foreach ($points as $point)
        {
            $dimension['x'] = $point->x < $dimension['x'] ? $point->x : $dimension['x'];
            $dimension['y'] = $point->y < $dimension['y'] ? $point->y : $dimension['y'];
            $dimension['width'] = $point->x > $dimension['width'] ? $point->x : $dimension['width'];
            $dimension['height'] = $point->y > $dimension['height'] ? $point->y : $dimension['height'];
        }

        $dimension['width'] -= $dimension['x'];
        $dimension['height'] -= $dimension['y'];

        return $dimension;
    }

    public function Doodle()
    {
        $dimension = $this->getDimension();
        $ratio = 1;
        if ($dimension['height'] > 125) {
            $ratio = 125 / $dimension['height'];
        }
        $canvas = LiteralField::create('canvas', '
            <canvas id="canvas-' . $this->ID . '"></canvas>
            <script>
                var canvas = document.getElementById("canvas-' . $this->ID . '");
                canvas.width = ' . ($dimension['width'] * $ratio + 10) . ';
                canvas.height = ' . ($dimension['height'] * $ratio + 10) . ';
                var ctx = canvas.getContext("2d");
                var points = ' . json_encode($this->Points()->format(array('x' => 'x', 'y' => 'y'))) . ';
                ctx.beginPath();
                ctx.strokeStyle = "#bae1ff";
                ctx.lineCap = "round";
                ctx.lineJoin = "round";
                ctx.lineWidth = 5;
                for (var i = 0; i < points.length; i++) {
                    ctx.moveTo(points[i].x * ' . $ratio . ' - ' . ($dimension["x"] * $ratio - 5) . ',points[i].y * ' . $ratio . ' - ' . ($dimension["y"] * $ratio - 5) . ');
                    if (i + 1 < points.length) {
                        ctx.lineTo(points[i+1].x * '. $ratio . ' - ' . ($dimension["x"] * $ratio - 5) . ',points[i+1].y * ' . $ratio . ' - ' . ($dimension["y"] * $ratio - 5) . ');
                        ctx.stroke();
                    }
                }
                ctx.closePath();

                ctx.beginPath();
                ctx.strokeStyle = "#df0000";
                ctx.moveTo(points[0].x * ' . $ratio . ' - ' . ($dimension["x"] * $ratio - 5) . ',points[0].y * ' . $ratio . ' - ' . ($dimension["y"] * $ratio - 5) . ');
                ctx.lineTo(points[0].x * ' . $ratio . ' - ' . ($dimension["x"] * $ratio - 5) . ',points[0].y * ' . $ratio . ' - ' . ($dimension["y"] * $ratio - 5) . ');
                ctx.stroke();
                ctx.closePath();
            </script>
        ');
        return $canvas;
    }
}
