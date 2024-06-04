import axios from "axios";

export default {
    namespaced: true,
    state: {
        token: null,
        user: null,
    },
    getters: {
        authenticated(state) {
            return state.token && state.user;
        },
        user(state) {
            return state.user;
        },
    },
    mutations: {
        SETTOKEN(state, token) {
            state.token = token
        },
        SETUSER(state, data) {
            state.user = data
        },
    },
    actions: {
        async login({ dispatch }, credentials) {
            let response = await axios.post("login", credentials);
            await dispatch("attempt", response.data.token);
            return response;
        },
        async register({ dispatch }, credentials) {
            let response = await axios.post("register", credentials);
            await dispatch("attempt", response.data.token);
            return response;
        },
        async attempt({ commit, state }, token) {
            if (token) {
                commit("SETTOKEN", token);
            }
            if (!state.token) {
                return;
            }
            try {
                let response = await axios.get("info");
                commit("SETUSER", response.data);
            } catch (error) {
                commit("SETTOKEN", null);
                commit("SETUSER", null);
            }
        },
        async logout({ commit }) {
            axios.get("logout");
            commit("SETTOKEN", null);
            commit("SETUSER", null);
        },
    },
};
