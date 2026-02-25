import api from './api';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { store } from '../store';
import { authActions } from '../store/authSlice';

const authService = {
  // User login
  async login(mobile, password) {
    try {
      const response = await api.post('/auth/login', { mobile, password });
      const { token, user } = response.data.data;

      // Save token
      await AsyncStorage.setItem('auth_token', token);
      await AsyncStorage.setItem('user', JSON.stringify(user));

      // Update Redux store
      store.dispatch(authActions.setUser(user));
      store.dispatch(authActions.setToken(token));

      return { success: true, user };
    } catch (error) {
      return { success: false, error: error.response?.data?.message || 'Login failed' };
    }
  },

  // User registration (for FA)
  async register(data) {
    try {
      const response = await api.post('/auth/register', data);
      return { success: true, data: response.data.data };
    } catch (error) {
      return { success: false, error: error.response?.data?.message || 'Registration failed' };
    }
  },

  // Logout
  async logout() {
    try {
      await api.post('/auth/logout');
      await AsyncStorage.removeItem('auth_token');
      await AsyncStorage.removeItem('user');
      store.dispatch(authActions.logout());
      return { success: true };
    } catch (error) {
      return { success: false, error: error.message };
    }
  },

  // Check if user is authenticated
  async checkAuthStatus() {
    try {
      const token = await AsyncStorage.getItem('auth_token');
      const user = await AsyncStorage.getItem('user');

      if (token && user) {
        store.dispatch(authActions.setToken(token));
        store.dispatch(authActions.setUser(JSON.parse(user)));
        return true;
      }
      return false;
    } catch (error) {
      console.error('Error checking auth status:', error);
      return false;
    }
  },

  // Get user profile
  async getProfile() {
    try {
      const response = await api.get('/auth/profile');
      return { success: true, user: response.data.data };
    } catch (error) {
      return { success: false, error: error.response?.data?.message };
    }
  },

  // Refresh token
  async refreshToken() {
    try {
      const response = await api.post('/auth/refresh');
      const { token } = response.data.data;
      await AsyncStorage.setItem('auth_token', token);
      store.dispatch(authActions.setToken(token));
      return { success: true, token };
    } catch (error) {
      return { success: false, error: error.message };
    }
  },

  // Password reset
  async passwordReset(mobile, newPassword) {
    try {
      const response = await api.post('/auth/password-reset', { mobile, new_password: newPassword });
      return { success: true, message: response.data.message };
    } catch (error) {
      return { success: false, error: error.response?.data?.message };
    }
  },
};

export default authService;
