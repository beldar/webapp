var Message = Backbone.Model.extend({
    initialize:function(){
        this.listenTo(this, "remove",this.destroy);
        this.listenTo(this, "media", this.showmedia);
        this.set('media', false);
    }
});
var MessageList = Backbone.Collection.extend({
   model: Message,
   localStorage: new Backbone.LocalStorage("conversa"),
   clear: function(){
       this.remove(this.models);
   },
   ismedia: function(){
       return this.filter(function(msg){return msg.get('media')});
   }
});
var MessageView = Backbone.View.extend({
    tagName: "p",
    initialize: function(){
        this.listenTo(this.model,"change", this.render);
        this.listenTo(this.model,"destroy", this.remove);
        this.listenTo(this.model, "change:media", this.rendermedia)
    },
    render: function(){
        this.$el.addClass("message").addClass(this.model.get('from'));
        this.$el.html(this.model.get('body'));
        var t = new Date(this.model.get('time'));
        var date = (t.getHours()<10 ? '0'+t.getHours() : t.getHours());
        date += ':'+(t.getMinutes()<10 ? '0'+t.getMinutes() : t.getMinutes());
        $('<div class="time">'+date+'</div>').appendTo(this.$el);
        if(this.model.get('from')=='you') $('<div class="pavatar"></div>').appendTo(this.$el);
        if(this.model.get('body').match(/<img/g)!=null)
            _.defer(function(){$("a.image").photoSwipe()});
        if(this.model.get('body').match(/<img/g)!=null || this.model.get('body').match(/<video/g)!=null)
            this.model.set('media', true).save();
        return this;
    },
    rendermedia: function(){
        console.log('Rendering media');
        var id = this.model.get('body').match(/<[^>]*src=["|\']([^"|\']+)/i)[1].replace(/\"/g,'').split('/').pop();
        if($("#"+id).length==0)
            $('<div id="'+id+'" class="media">'+this.model.get('body')+'</div>').appendTo('#mediacont');
        $(window).trigger('newmedia');
    },
    save: function(){
        this.model.save();
    },
    clear: function(){
        console.log('cleaning up');
        this.model.destroy();
    }
});
var User = Backbone.Model.extend({
    idAttribute: "id",
    localStorage: new Backbone.LocalStorage("user")
});
var AppView = Backbone.View.extend({
   el: $("#whatsfake"),
   events:{
       "click #send":"say",
       "keypress #input":"saypress",
       "click #clear":"clearconv",
       "click #media":"showmedia",
       "click #anonim":"anonim"
   },
   initialize: function(){
       var view = this;
       this.user = new User();
       this.user.fetch({
           success: function(){
                console.log('Fetch success');
                if(view.user.id===undefined){
                    console.log('Fake success');
                    view.newUser();
                }else{
                    view.setup();
                }
           },
           error: function(){
               console.log('Fetch fail');
               view.newUser();
           }
       });
       

   },
   showmedia: function(){
        if($("#mediacont .media").length==0) $("#mediacont").append('<h2>No hay contenido multimedia</h2>');
        $("#container").addClass('flip');
        $('<a href="#" id="back" class="btn btn-inverse pull-left" style="margin-left:20px"><i class="icon-circle-arrow-left"></i> atrás</a>').appendTo('#title');
        $("#back").click(function(){
            $("#container").removeClass('flip');
            $(this).remove();
        })
   },
   clearconv: function(){
       this.Conversa.clear();
   },
   anonim: function(){
       if($("#name").val()!=""){
           var name = $("#name").val();
           var firstname = name.split(' ')[0];
           window.user = {
                    "identifier": "",
                    "webSiteURL": "",
                    "profileURL": "",
                    "photoURL": "",
                    "displayName": name,
                    "description": "",
                    "firstName": firstname,
                    "lastName": "",
                    "gender": "",
                    "language": "",
                    "age": "",
                    "birthDay": "",
                    "birthMonth": "",
                    "birthYear": "",
                    "email": "",
                    "emailVerified": "",
                    "phone": "",
                    "address": "",
                    "country": "",
                    "region": "",
                    "city": "",
                    "zip": ""
           };
           window.provider = "none";
           this.newUser();
       }
   },
   newUser: function(){
        var view = this;
        if(window.user!=null){
            console.log(window.user);
            $.post("newuser.php", {"user":window.user, "provider":window.provider}, function(r){
                eval("var data = "+r);
                if(data.user && data.body){
                    console.log('New user');
                    view.user.set(data.user);
                    view.user.save();
                    var msg = new Message({from:'you',body:data.body,time:new Date(), id:new Date().getTime()});
                    view.setup(msg);
                }else if(data.error){
                    console.log(data.error);
                }else{
                    console.log(r);
                }
            })
            .fail(function(xhr,str){
                alert('Parece que no tienes conexión, no puedo registrarte como nuevo usuario.');
            }); 
        }else{
            console.log('No user: '+ window.user);
        }
   },
   setup: function(msg){
        this.id = this.user.get('id');
        
        this.Conversa = new MessageList;
        this.listenTo(this.Conversa, 'add', this.addOneSave);
        this.listenTo(this.Conversa, 'reset', this.addAll);
        this.Conversa.fetch();
        this.input = this.$('#input');
        this.send = this.$("#send");
        this.media = this.$('#mediacont');
        this.register = this.$("#registre");
        if(msg) this.Conversa.add(msg);
        this.register.hide();
   },
   render: function(){
       
   },
   addOne: function(msg){
       this.Conversa.add(msg);
       var view = new MessageView({model:msg});
       this.$("#bubbles").append(view.render().el);
   },
   addOneSave: function(msg){
       var view = new MessageView({model:msg});
       view.save();
       this.$("#bubbles").append(view.render().el);
   },
   addAll: function(){
       this.Conversa.each(this.addOne,this);
       _.defer(this.createScroll);
   },
   saypress: function(e){
       if (e.keyCode != 13) return;
       if (!this.input.val()) return;
       this.say();
   },
   say: function(){
       var val = this.input.val();
       var view = this;
       if(val!=''){
            // Strip tags
            val = val.replace(/(<([^>]+)>)/ig,"");
            var msg = new Message({from:'me',body:val, time:new Date(), id:new Date().getTime()})
            this.Conversa.add(msg);
            $.post('talk.php',{input:val,user:this.id}, function(r){
                    var msg = new Message({from:'you',body:r, time:new Date(), id:new Date().getTime()})
                    view.Conversa.add(msg);
                    view.input.val('');
                    _.defer(view.scrollDown);
            }).error(function(){
                    var msg = new Message({from:'you',body:'Lo siento, pero parece que no hay conexión, así que tendremos que dejar la charla para más tarde :(', time:new Date(), id:new Date().getTime()})
                    view.Conversa.add(msg);
                    view.input.val('');
                    _.defer(view.scrollDown);
            });
       }
   },
   createScroll: function(){
       setTimeout(function () {
            window.scroller = new iScroll('conversation', {hScrollbar:false, bounce:false})
                window.mscroller = new iScroll('mediadiv', {hScrollbar:false, bounce:false})
                window.scroller.scrollTo(0,-$("#bubbles").height(),0);
                $(window).bind('newmedia', window.refreshmedia);
	}, 0); 
   },
   scrollDown: function(){
       setTimeout(function () {
		window.scroller.refresh();
                var lasth = parseInt($("#bubbles p").last().outerHeight())+10;
                lasth += parseInt($("#bubbles p").last().prev().outerHeight())+10;
                console.log(lasth);
                window.scroller.scrollTo(0,lasth,0, true);
                //$("#conversation").scrollTop($("#bubbles").height());
	}, 0); 
   }
});
var scroller = false;
var mscroller = false;
function refreshmedia(){
   console.log('refreshing media');
   setTimeout(function () {
            window.mscroller.refresh();           
    }, 0);
}
function c(str){
   console.log(str);
}