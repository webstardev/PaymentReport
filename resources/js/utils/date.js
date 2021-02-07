import moment from 'moment';
export const getDateRange = range => {
  if (range.toLowerCase() === 'today') {
    return {
      startDate: moment(new Date()).startOf('day'),
      endDate: moment(new Date()).endOf('day')
    };
  } else if (range.toLowerCase() === 'yesterday') {
    return {
      startDate: moment(new Date())
        .subtract(1, 'day')
        .startOf('day'),
      endDate: moment(new Date())
        .subtract(1, 'day')
        .endOf('day')
    };
  } else if (range.toLowerCase() === 'this month') {
    return {
      startDate: moment(new Date()).startOf('month'),
      endDate: moment(new Date()).endOf('month')
    };
  } else if (range.toLowerCase() === 'last month') {
    return {
      startDate: moment(new Date())
        .subtract(1, 'month')
        .startOf('month'),
      endDate: moment(new Date())
        .subtract(1, 'month')
        .endOf('month')
    };
  } else
    return {
      startDate: moment(new Date()).startOf('day'),
      endDate: moment(new Date()).endOf('day')
    };
};
