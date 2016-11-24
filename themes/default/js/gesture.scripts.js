window.drawLocked   =   false;
window.lastGesture  =   null;
$(document).ready(function(e){

    var _stage        = {
            canvas:     document.getElementById('stage'),
            context:    document.getElementById('stage').getContext('2d')
        },
        oldX,
        oldY,
		canvas,
		ctx           =   _stage.context,
		_r            =   null,
		_points       =   [],
		isMouseDown   =   false,
		threshold     =   3,
        _panning        =   null;
        _isLine         =   true;

    $.get('/api/v/1/gestures', function(data) {
        trace(data);
        _r = new DollarRecognizer(data);
    });

    $(window).resize(function(e){
        _stage.canvas.width = $(window).width();
        _stage.canvas.height = $(window).height();
    }).resize();

    var myElement = document;

    // create a simple instance
    // by default, it only adds horizontal recognizers
    var mc = new Hammer(myElement);

    // let the pan gesture support all directions.
    // this will block the vertical scrolling on a touch-device while on the element
    mc.get('pan').set({ direction: Hammer.DIRECTION_ALL });

    // listen to events...
    mc.on("panleft panright panup pandown", function(ev) {
        //console.log(ev.type +" gesture detected.");
        if (!_panning) {
            _panning = ev.type;
        } else {
            if (_panning != ev.type) {
                _isLine = false;
            }
        }
    });

    var downHandler = function(e) {
        if (!$(e.target).is('#stage')) {
            isMouseDown = false;
            return;
        }

        if (window.drawLocked) {
            return;
        }
        _panning = null;
        _isLine = true;
        isMouseDown = true;
        e.preventDefault();
        _points = [];
        ctx.beginPath();
        ctx.strokeStyle = "#bae1ff";
        ctx.lineCap = "round";
        ctx.lineJoin = "round";
        ctx.lineWidth = 6;

        oldX = e.pageX ? e.pageX : (e.touches && e.touches.length > 0 ? e.touches[0].pageX : oldX);
        oldY = e.pageY ? e.pageY : (e.touches && e.touches.length > 0 ? e.touches[0].pageY : oldY);
        _points.push(new Point(oldX, oldY));
    };

    var moveHandler = function(e) {
        if (window.drawLocked) {
            return;
        }
		if (!isMouseDown) {
			return;
		}

		ctx.moveTo(oldX,oldY);
        oldX = e.pageX ? e.pageX : (e.touches && e.touches.length > 0 ? e.touches[0].pageX : oldX);
        oldY = e.pageY ? e.pageY : (e.touches && e.touches.length > 0 ? e.touches[0].pageY : oldY);
        
		ctx.lineTo(oldX,oldY);
		ctx.stroke();
		_points[_points.length] = new Point(oldX,oldY);
    };

    var upHandler = function(e) {
        if (window.drawLocked) {
            return;
        }
        var strPoints = '';
        for (var i = 0; i < _points.length; i++) {
            strPoints += 'new Point(' + _points[i].X + ',' + _points[i].Y + ')';
            if (i < _points.length - 1) {
                strPoints += ',';
            }
        }

        //console.log(strPoints);

		isMouseDown = false;
		ctx.closePath();
        if (_isLine) {
            if (_points.length == 1) {
                trace('a dot');
            } else {
                var gesture = '';
                switch (_panning) {
                    case 'panleft':
                        gesture = 'strike left';
                        break;
                    case 'panright':
                        gesture = 'strike right';
                        break;
                    case 'panup':
                        gesture = 'strike up';
                        break;
                    case 'pandown':
                        gesture = 'strike down';
                        break;
                }
                //var s = '\'me guess... it\'s <strong>'+gesture+'</strong> gesture.';
                trace(gesture);
            }
        } else {
    		if (_points.length >= 10) {
                if (_r !== null) {
                    var result = _r.Recognize(_points);
                    //var s = '\'me guess... it\'s <strong>'+result.Name+'</strong>, likelihood: <strong>'+Math.round(result.Score*100) + "%"+'</strong>';
                    trace(result.Name + ', ' + Math.round(result.Score*100) + "%");
                    var record = new Record(result.Name, result.Score, _points);
                    if (window.lastGesture) {
                        window.lastGesture.dismiss();
                    }
                    window.lastGesture = record;
                    $('header').append(record.form);
                    setTimeout(function()
                        {
                            $(record.form).removeClass('ready');
                        },
                        10
                    );

                }
    		} else if (_points.length == 1) {
                //var s = 'It\'s a dot (you just simply tapped on the screen)';
                trace('a dot');
            }
        }
        _panning = null;
        _isLine = true;
		//ctx.clearRect(0,0,_stage.canvas.width,_stage.canvas.height);
        //animateUserInput(_points, ctx);
        if (_points && _points.length > 0) {
            animateCanvas(_stage);
        }

        _points = [];
    };

    document.addEventListener('mousedown', downHandler, false);
    document.addEventListener('touchstart', downHandler, false);

    document.addEventListener('mousemove', moveHandler, false);
    document.addEventListener('touchmove', moveHandler, false);

    document.addEventListener('mouseup', upHandler, false);
    document.addEventListener('touchend', upHandler, false);


});

function animateCanvas(stage) {
    window.drawLocked = true;
    var ctx = stage.context;
    var canvas = stage.canvas;
    var duplicate = canvas.cloneNode();
    var opacity = 1;
    var scaleRate = 1;
    duplicate.getContext('2d').drawImage(canvas, 0, 0);
    ctx.translate(canvas.width * 0.5, canvas.height * 0.5);
    var animation = setInterval(function(){
        if (Math.ceil(opacity) > 0) {
            ctx.clearRect($(window).width() * -1,$(window).height()*-1,$(window).width() * 2,$(window).height()*2);
            ctx.scale(scaleRate,scaleRate);
            ctx.globalAlpha = opacity;
            ctx.drawImage(duplicate, duplicate.width * -0.5, duplicate.height * -0.5);
            opacity -= 0.05;
            scaleRate += 0.002;
        } else {
            clearInterval(animation);
            ctx.globalAlpha = 1;
            ctx.setTransform(1, 0, 0, 1, 0, 0);
            ctx.clearRect(0,0,$(window).width(),$(window).height());
            window.drawLocked = false;
        }
    }, 8);
}
