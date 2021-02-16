import axios from 'axios';
import Swal from 'sweetalert2';
import store from '@/services/store';
import { PURGE_AUTH } from '@/services/store/auth.module.js';
import router from '@/router';

// Request interceptor
axios.interceptors.request.use(request => {
  const token = store.getters.token;
  if (token) {
    request.headers.common['Authorization'] = `Bearer ${token}`;
  }

  const locale = store.getters['lang/locale'];
  if (locale) {
    request.headers.common['Accept-Language'] = locale;
  }

  return request;
});

// Response interceptor
axios.interceptors.response.use(
  response => response,
  error => {
    const { status } = error.response;

    if (status >= 500) {
      Swal.fire({
        title: 'Error',
        icon: 'error',
        text: JSON.stringify(error.response.data.message),
        reverseButtons: true,
        confirmButtonText: 'ok',
        cancelButtonText: 'cancel'
      });
      return Promise.reject(error);
    } else if (status === 401) {
      Swal.fire({
        title: 'Token expired',
        icon: 'warning',
        text: 'Token expired, need to login again.'
      }).then(result => {
        store.commit(PURGE_AUTH);
        router.push('/login');
      });
    } else {
      return Promise.reject(error);
    }
  }
);
