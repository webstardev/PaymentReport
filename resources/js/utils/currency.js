export const calculateCurrency = (originValue, currencyData) => {
  if (currencyData.rates[originValue.currency]) {
    return originValue.sum / currencyData.rates[originValue.currency];
  }
  return originValue.sum;
};
