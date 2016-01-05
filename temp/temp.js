var AppointmentView = Backbone.View.extend({
    template: _.template('<span><%= title =%></span>'),

    render: function(){
        var attributes = this.model.toJSON();
        this.$el.html(this.template(attributes));
    }
});

