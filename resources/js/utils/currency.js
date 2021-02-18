export const calculateCurrency = (originValue, currencyData) => {
  debugger;
  if (currencyData.rates[originValue.currency]) {
    return originValue.sum / currencyData.rates[originValue.currency];
  }
  return originValue.sum;
};
