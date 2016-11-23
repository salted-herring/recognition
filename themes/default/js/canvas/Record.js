var Record = function(title, likelihood, points)
{
    var self        =   this,
        thumbnail   =   new Sprite(100, 100),
        input       =   $('<input type="text" class="gesture-name" name="Title" />'),
        hood        =   $('<div />').addClass('hood'),
        btnY        =   $('<button />').addClass('btn').append('Cool'),
        btnN        =   $('<button />').addClass('btn').append('Wrong'),
        submit      =   $('<input type="submit" value="Learn" class="hide" />');

    this.points = [];
    this.dimension = {
        x: points && points.length > 0 ? points[0].X : 0,
        y: points && points.length > 0 ? points[0].Y : 0,
        width: points && points.length > 0 ? points[0].X : 0,
        height: points && points.length > 0 ? points[0].Y : 0
    };

    if (points && points.length > 0) {
        for (var n = 0; n < points.length; n++)
        {
            this.points.push(new Point(points[n].X, points[n].Y));
            this.dimension.x = points[n].X < this.dimension.x ? points[n].X : this.dimension.x;
            this.dimension.y = points[n].Y < this.dimension.y ? points[n].Y : this.dimension.y;
            this.dimension.width = points[n].X > this.dimension.width ? points[n].X : this.dimension.width;
            this.dimension.height = points[n].Y > this.dimension.height ? points[n].Y : this.dimension.height;
        }

        this.dimension.width -= this.dimension.x;
        this.dimension.height -= this.dimension.y;
    }

    this.form = $('<form />').addClass('gesture-record as-flex wrap');
    this.form.attr('action', '/api/v/1/gestures').attr('method', 'post');


    hood.append($('<div />').addClass('likelihood').append(Math.round(likelihood*100) + "%"));
    if (likelihood < 0.8) {
        input.attr('placeholder', 'what is this gesture?');
        hood.hide();
    } else {
        input.val(title).attr('placeholder', title);
    }

    hood.append($('<div />').addClass('btns').append(btnY, btnN));

    this.form.append(thumbnail.canvas, input, hood, submit);

    this.draw = function()
    {
        var ctx     =   thumbnail.graphics(),
            ratio   =   96 / (self.dimension.width > self.dimension.height ? self.dimension.width : self.dimension.height),
            tX      =   self.dimension.width * ratio < 96 ? (96 - self.dimension.width * ratio) * 0.5 : 0,
            tY      =   self.dimension.height * ratio < 96 ? (96 - self.dimension.height * ratio) * 0.5 : 0;

        ctx.beginPath();
        ctx.strokeStyle = "#bae1ff";
        ctx.lineCap = "round";
        ctx.lineJoin = "round";
        ctx.lineWidth = 2;
        ctx.translate(tX, tY);
        for (var i = 0; i < self.points.length; i++) {
            var X   =   (self.points[i].X - self.dimension.x) * ratio + ctx.lineWidth,
                Y   =   (self.points[i].Y - self.dimension.y) * ratio + ctx.lineWidth;
            ctx.moveTo(X, Y);
            if (i + 1 < self.points.length) {
                X   =   (self.points[i+1].X - self.dimension.x) * ratio + ctx.lineWidth,
                Y   =   (self.points[i+1].Y - self.dimension.y) * ratio + ctx.lineWidth;
                ctx.lineTo(X, Y);
                ctx.stroke();
            }
        }
        ctx.closePath();
    };

    this.draw();

    return this;
};
