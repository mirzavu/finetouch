Validation.add('validate-per-page-value-list-fix', 'Please enter a valid value which not contain 0, ex: 10,20,30', function(v) {
            var isValid = !Validation.get('IsEmpty').test(v);
            var values  = v.split(',');
            for (var i=0;i<values.length;i++) {
                if (!/^[0-9]+$/.test(values[i]) || values[i] == 0) {
                    isValid = false;
                }
            }
            return isValid;
        });