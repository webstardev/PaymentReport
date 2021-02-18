import axios from 'axios';

export const getBrand = (id = 'all') => {
  return new Promise((resolve, reject) => {
    try {
      axios
        .get(`/api/brand/${id}`)
        .then(res => {
          if (res && res.data) {
            resolve(res.data);
          }
          reject([]);
        })
        .catch(err => {
          reject([]);
        });
    } catch (err) {
      reject([]);
    }
  });
};

export const getPaymentMethod = (id = 'all') => {
  return new Promise((resolve, reject) => {
    try {
      axios
        .get(`/api/payment-method/${id}`)
        .then(res => {
          if (res && res.data) {
            resolve(res.data);
          }
          reject([]);
        })
        .catch(err => {
          reject([]);
        });
    } catch (err) {
      reject([]);
    }
  });
};

export const getExpensesType = (id = 'all') => {
  return new Promise((resolve, reject) => {
    try {
      axios
        .get(`/api/expenses-type/${id}`)
        .then(res => {
          if (res && res.data) {
            resolve(res.data);
          }
          reject([]);
        })
        .catch(err => {
          reject([]);
        });
    } catch (err) {
      reject([]);
    }
  });
};
