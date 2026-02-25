import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { store } from './store';
import { authActions } from './store/authSlice';

const API_URL = 'http://192.168.1.100:8000/api/v1'; // Change to your server IP

const api = axios.create({
  baseURL: API_URL,
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Add token to requests if available
api.interceptors.request.use(async (config) => {
  try {
    const token = await AsyncStorage.getItem('auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
  } catch (error) {
    console.error('Error retrieving token:', error);
  }
  return config;
});

// Handle response errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Token expired or invalid
      store.dispatch(authActions.logout());
    }
    return Promise.reject(error);
  }
);

export default api;
