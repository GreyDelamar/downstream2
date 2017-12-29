import _ from 'lodash';
import * as types from '../mutation-types';

const state = {
  videos: [],
  queue: [],
  playingVideo: false,
  currentVideo: false,
};

const getters = {
  collection(state, getters, rootState) {
    return rootState.collection.videos
  },
  queue(state) {
    return state.queue;
  },
};

const actions = {
  playNextVideo({ commit }, { id }) {
    commit(type.MEDIA_PLAY_NEXT_VIDEO);
  },
  playVideo({ commit }, { id }) {
    commit(types.MEDIA_UPDATE_CURRENT_VIDEO, { id });
    commit(types.MEDIA_PLAY_VIDEO);
  },
  updateCurrentVideo({ commit }, { id }) {
    commit(types.MEDIA_UPDATE_CURRENT_VIDEO, { id });
  },
  pauseVideo({ commit }, { id }) {
    commit(types.MEDIA_PAUSE_VIDEO, { id });
  },
  registerVideo({ commit }, { id, player }) {
    commit(types.MEDIA_REGISTER_VIDEO, {
      id,
      player,
    });
  },
  destroyVideo({ commit }, { id }) {
    commit(types.MEDIA_DESTROY_VIDEO, { id });
  },
};

const mutations = {
  [types.THEATER_UPDATE_QUEUE](state, videos) {
    state.queue = videos;
  },
  [types.MEDIA_PLAY_NEXT_VIDEO](state) {
  },
  [types.MEDIA_PLAY_VIDEO](state) {
    const video = state.currentVideo;
    video.player.playVideo();
    state.playing = true;
  },
  [types.MEDIA_PAUSE_VIDEO](state, { id }) {
    const video = state.currentVideo;
    video.player.pauseVideo();
    state.playing = false;
  },
  [types.MEDIA_UPDATE_CURRENT_VIDEO](state, { id }) {
    const index = _.findIndex(state.videos, video => video.id == id);

    const video = state.videos[index];
    state.currentVideo = video;
  },
  [types.MEDIA_REGISTER_VIDEO](state, { id, player }) {
    state.videos.push({
      id,
      player,
    });
  },
  [types.MEDIA_DESTROY_VIDEO](state, { id }) {
    state.videos = _.remove(state.videos, video => video.id == id);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
