var SaltedIO = function()
{
    this.store = function(key, value)
    {
        if (typeof value == 'object') {
            value = JSON.stringify(value);
        }

        localStorage.setItem(key, value);
    };

    this.retrieve = function(key)
    {
        var value = localStorage.getItem(key);
        try {
            value = JSON.parse(value);
        } catch (e) {
            trace('this no json - return original value');
        }

        return value;
    }

    this.delete = function(key)
    {
        localStorage.removeItem(key);
    };

    return this;
};
