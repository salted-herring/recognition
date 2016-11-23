var Sprite = function(w, h)
{
    var self = this,
        canvas = $('<canvas />').addClass('sprite');
    this.canvas = canvas[0];
    this._width = w !== undefined ? w : 100;
    this._height = h !== undefined ? h : 100;
    this.canvas.width = this._width;
    this.canvas.height = this._height;

    this.graphics = function()
    {
        return self.canvas.getContext('2d');
    };

    this.width = function(n)
    {
        if (n !== null && n !== undefined) {
            self._width = self.canvas.width = n;
        }

        return self._width;
    };

    this.height = function(n)
    {
        if (n !== null && n !== undefined) {
            self._height = self.canvas.height = n;
        }

        return self._height;
    };

    return this;
};
