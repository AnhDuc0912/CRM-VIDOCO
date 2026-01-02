const PaymentPeriodEnum = {
  YEAR: 1,
  MONTH: 2,
  getLabel: function(value) {
    switch (value) {
      case this.YEAR:
        return 'Năm';
      case this.MONTH:
        return 'Tháng';
    }
  }
};
