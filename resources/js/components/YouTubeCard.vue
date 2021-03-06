<template>
  <div :id="sessionId + '_card'" :class="{ card: true , 'border-info': false, 'mx-auto': false, 'mb-4': true }">
    <div id="cardToolbar" class="card-block">
      
      <!-- Card Buttons -->
      <div class="float-right">
        <a v-if="mediaId" target="_blank" :href="/v/ + videoId" class="btn btn-outline-primary" aria-haspopup="true" aria-expanded="false"><i class="fas fa-link"></i> Direct Link</a>
        <button v-if="globalQueued && showGlobalQueue && !clientOnMobile"  @click="pushGlobalQueue" class="btn btn-info"><i class="fa fa-share" aria-hidden="true"></i> Queued </button>
        <button v-if="!globalQueued && showGlobalQueue && !clientOnMobile"  @click="pushGlobalQueue" class="btn btn-outline-info"><i class="fa fa-share" aria-hidden="true"></i> Global Queue</button>
        <button v-if="!collected" @click="discover" class="btn btn-success">Collect <i class="fas fa-plus-circle"></i></button>
        <button v-if="collected" @click="toss" class="btn btn-primary">Toss <i class="fas fa-minus-circle"></i></button>  
      </div>
    </div>

    <div v-if="!playing" :id="this.sessionId + '_media'" class="media-container">

      <div @click="parentPlay">
        <img style="width:100%; height: 100%"  :id="this.sessionId + '_thumbnail'" class="img-fluid img" :src="formatThumbnail" />
      </div>

      <div class="col">
        <div class="col-sm-12 text-truncate">
          <p style="color:white;">{{ title }}</p>
        </div>
      </div>
    </div>

    <!-- YouTube Player -->
    <div class="video-instance embed-responsive" :id="sessionId"></div>

    <div v-if="displayName" class="card-footer">
      <span>Collected by <a>@{{ displayName }}</a></span> 
      <span class="float-right">
        <i class="far fa-clock"></i> {{ createdAt }} days ago  
      </span>
    </div>
  </div>
</template>

<script>
    import $ from 'jquery';
    import ColorThief from 'colorthief';
    import SID from 'shortid';
    import MobileDetect from 'mobile-detect';
    import YTPlayer from 'yt-player';
    import CardGlow from '../services/CardGlow';
    import { generateElementId, clientOnMobile } from '../services/Utils';

    let detect = new MobileDetect(window.navigator.userAgent);

    //Component Props
    const props = {
      sessionId: {
        type: String,
        default: () => { return generateElementId() }
      },
      createdAt: String,
      displayName: String,
      spotifyId: String,
      ownerId: Number,
      mediaId: Number,
      videoId: String,
      title: String,
      startAtTimestamp: {
        type: Number,
        default: 0
      },
      shouldPlay: Boolean,
      shouldPlayNext: {
        type: Boolean,
        default: true
      },
      collected: Boolean,
      thumbnail: String,
      thumbnailColors: String,
      globalQueued: {
        type: Boolean,
        default: false,
      },
      showGlobalQueue: {
        type: Boolean,
        default: false
      }
    };

    let data = () => {
      return {
        colorString: false,
        playing: false,
        player: false,
        showThumbnail: true,
        showVideo: false
      }
    }

    const computed = {
      clientOnMobile() {
        return detect.mobile();
      },
      userIsAdmin() {
        return this.$store.getters['user/isAdmin'];
      },
      userId() {
        return this.$store.state.user.id;
      },
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
      name:"youtube-card",
      computed: computed,
      props: props,
      data: data,
      /**
       * @EVENT mounted
       */
      mounted() {
        /**
          MOUNTED EVENT
         */
        this.playerRegister();

        if(this.shouldPlay) {
          setTimeout(() => {
            this.$store.dispatch('player/play', this.sessionId)
          }, 2000);
        }

        //color thief
      },
      destroyed() {
        //clean up player
        //fixes issue with player not being attached to dom after search update
        this.player = false;
      },
      methods: {
        parentPlay() {
          this.$store.dispatch('player/play', this.sessionId)
        },

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
          const elementId = "#" + this.sessionId.replace(/["\\]/g, '\\$&');
          const options = {
            volume: 5,
            fullscreen: true,
            playsinline: true,
            height: $(`#${this.sessionId}_media`).height(),
            width: $(`#${this.sessionId}_media`).width()
          };

          let player = new YTPlayer($(elementId)[0], options);
          player.load(this.videoId);
          
          this.player = player;
        },
        /**
         * Play
         * triggered by clicking thumbnail
         */
        play(volume) {
          if(!this.player) {
            this.loadVideo();
          }

          this.player.setVolume(volume);
          if(this.startAtTimestamp) {
            this.player.seek(this.startAtTimestamp);
          }
          
          this.player.play();

          gtag('event', 'play', {
            'event_category' : 'Media',
            'event_label' : this.videoId
          });

          if(this.mediaId) {
            $analytics.recordMediaPlay(this.mediaId);
          }
        
          this.playing = true;
          var glow = new CardGlow()

          glow.enableElementGlow("#" + (this.sessionId + '_card'), this.thumbnailColors);

          this.player.on('unplayable', (err) => {
           console.error(this.videoId + " is unplayable")
          })

          this.player.on('timeupdate', (seconds) => {
            //console.log(seconds + " of " + this.player.getDuration());
          })

          this.player.on('unstarted', (err) => {
            if(this.clientOnMobile) {
              this.$store.dispatch('player/updatePlayingState', false);
              glow.disableElementGlow("#" + (this.sessionId + '_card'));
            }
          })

          this.player.on('ended', () => {
            glow.disableElementGlow("#" + (this.sessionId + '_card'));
            
            //reset video
            this.player.seek(0);
            this.player.pause();
            
            let stepForward = _.debounce(() => {
              console.log(this.sessionId + " telling player to indexStepForward");
              this.$store.dispatch('player/indexStepForward');
            }, 1500);

            if(this.shouldPlayNext) {
              stepForward();
            }
            
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
          this.collected = true;

          this.$store.dispatch('collection/discover', {
            type: 'youtube',
            videoId: this.videoId,
            spotifyId: this.spotifyId
          }).then((err, resp) => {
            this.$store.dispatch('collection/update');
          }, (err) => {
            this.collected = false;
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
            mediaId: this.mediaId
          }).then((resp) => {
            this.playerDeregister();
            this.$emit('tossed');
          });
          
          this.collected = false;
        },
        getMediaMeta() {
          return {
            title: this.title,
            mediaId: this.mediaId,
            videoId: this.videoId,
            thumbnail: this.thumbnail
          }
        },
        pushGlobalQueue() {
          const data = {
            mediaId:this.mediaId
          };
          axios.post('/api/global/push', data).then((response) => {
            this.showGlobalQueue = true;
            this.globalQueued = true;
          });
        },
        directLink() {
          window.location.href = "/v/" + this.videoId;
        },
        parentCallbackHandler(callback) {
          let self = this;

          callback(self);
        },
        toggleThumbnail(bool) {
          if(bool) {
            $("#" + this.sessionId + "_thumbnail").show();
            $("#" + (this.sessionId + '_card')).attr('style', '');
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
.center {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 50%;
}

.media-icon {
  margin-right: 10px;
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