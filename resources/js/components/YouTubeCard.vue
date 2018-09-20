<template>
  <div :class="{ card: true , 'border-info': false, 'mx-auto': true }">

    <div id="cardToolbar" class="card-block">
      <!-- ADMIN THINGS -->
      <div class="float-left">
        <p>SessionID=>{{ sessionId }}</p>
      </div>



      <div class="float-right">
        <div v-if="mediaId" class="btn-group">
          <button type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Links
          </button>
          <div class="dropdown-menu">
            <a class="dropdown-item" :href="'/v/' + getVid">Direct Link</a>
            <a  class="dropdown-item" >User Link</a>
          </div>
        </div>
        <button v-if="!collected" @click="discover" class="btn btn-outline-success">Collectx</button>
        <button v-if="collected" @click="toss" class="btn btn-success">Collected</button>
      </div>
    </div>

    <div v-if="!playing" :id="this.sessionId + '_media'" class="media-container">

      <img  v-on:click="$store.dispatch('player/play', sessionId)" :id="this.sessionId + '_thumbnail'" class="img-fluid" :src="formatThumbnail" />
      <div class="col">
        <div class="col-sm-12">
          <p style="color:white;">{{ title }}</p>
        </div>
      </div>
    </div>
    <!-- YouTube Player -->
    <div class="video-instance border-success" :id="this.sessionId"></div>
  </div>
</template>

<script>
    import $ from 'jquery';
    import SID from 'shortid';
    import YTPlayer from 'yt-player';

    let Utils = window._utils;
    window.ytp = YTPlayer;

    //Component Props
    const props = {
      sessionId: String,
      mediaId: Number,
      videoId: String,
      title: String,
      shouldPlay: Boolean,
      collected: Boolean,
      thumbnail: String
    };

    let data = () => {
      return {
        playing: false,
        player: false,
        showThumbnail: true,
        showVideo: false
      }
    }

    const computed = {
      formatThumbnail() {
        if(this.hasBadThumbnail == true) {
          return "https://via.placeholder.com/640x480/000000?text=" + this.title;
        }

        return this.thumbnail;
      },
      hasBadThumbnail() {
        return (this.thumbnail.includes('/default.jpg'));
      },
    }

    export default {
      computed: computed,
      props: props,
      data: data,
      /**
       * @EVENT mounted
       */
      mounted() {
        this.playerRegister();
      },
      destroyed() {
        //this.playerDeregister();
      },
      methods: {
        /**
         * Register With Player
         * 
         * passes item info to player to we can do things like play next track
         */
        playerRegister() {
          this.$store.dispatch('player/register', {
            sessionId: this.sessionId,
            media: this.getMediaMeta(),
            callbackHandler: this.parentCallbackHandler
          })
        },
        playerDeregister() {
          this.$store.dispatch('player/deregister', {
            sessionId: this.sessionId,
            media: this.getMediaMeta(),
            callbackHandler: this.parentCallbackHandler
          })
        },
        /**
         * Load Video
         * doing loading of video so it can play
         */
        loadVideo() {
          const options = {
            volume: 5,
            height: $(`#${this.sessionId}_media`).height(),
            width: $(`#${this.sessionId}_media`).width()
          };

          if(!$('#' + this.sessionId)) {
            console.log("cant load " + sessionId + " because dom element is not attached");
            return;
          }

          let player = new YTPlayer("#" + this.sessionId, options);
          player.load(this.videoId);

          this.player = player;
        },
        /**
         * Play
         * triggered by clicking thumbnail
         */
        play() {
          if(!this.player) {
            this.loadVideo();
          }

          this.player.play();
          this.playing = true;

          this.player.on('ended', () => {
            this.$store.dispatch('player/indexStepForward');
          })

          this.toggleThumbnail(false);
          this.toggleVideo(true);
        },
        pause() {
          this.player.pause();
          this.playing = false;

          this.toggleThumbnail(true);
          this.toggleVideo(false);
        },
        /**
        * Discover
        * add media to database if not already there
        * add to users collection
        */
        discover() {
          this.$store.dispatch('collection/discover', {
            type: 'youtube',
            videoId: this.getVid,
            spotifyId: this.spotifyId
          }).then((err, resp) => {
            this.isCollected = true;
            this.$store.dispatch('media/getCollection');
          }, (err) => {
            this.isCollected = false;
            $('#modals').show();
            this.$root.$emit('bv::show::modal','registerModal')
          });
        },
        /**
         * Toss
         * remove media item from collection but keep in database for others to collect
         */
        toss() {
          this.$store.dispatch('collection/toss', {
            type: 'youtube',
            mediaId: this.media.id,
          }).then((resp) => {
            this.$emit('tossed');
          });
          
          this.isCollected = false;
        },
        getMediaMeta() {
          return {
            title: this.title,
            mediaId: this.mediaId,
            videoId: this.videoId,
            thumbnail: this.thumbnail
          }
        },
        parentCallbackHandler(callback) {
          let self = this;

          callback(self);
        },
        toggleThumbnail(bool) {
          if(bool) {
            $("#" + this.sessionId + "_thumbnail").show();
          }else{
            $("#" + this.sessionId + "_thumbnail").hide();
          }
        },
        toggleVideo(bool) {
          if(bool) {
            $("#" + this.sessionId).show();
          }else{
            $("#" + this.sessionId).hide();
          }
        }
      }
    }
</script>

<style scoped>
.media-icon {
  margin-right: 10px;
}
.card {
  margin-bottom: 20px;
}
#cardToolbar {
  margin: 15px 15px 15px 15px;
}

.media-container {
  position: relative;
} 
.media-container .col {
  position: absolute; 
  z-index: 1; 
  top: 0; 
  left: 0; 
  color: white; 
  margin-top: 10px;
}
</style>