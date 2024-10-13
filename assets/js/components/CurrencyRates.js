import React, { Component } from 'react';

class CurrencyRates extends Component {
    constructor() {
        super();
        let date;
        const dateStr = new URLSearchParams(window.location.search).get('date');
        if (dateStr && this.isValidDate(dateStr)) {
            date = new Date(dateStr);
        } else {
            date = new Date();
            this.updateDateParam(date);
        }
        this.state = {
            loading: true, date: this.formatDate(date),
            rates: null, error: null,
            notFound: null
        };
        this.handleDateChange = this.handleDateChange.bind(this);
    }

    updateDateParam(date) {
        console.log('updateDateParam', date);
        const params = new URLSearchParams(window.location.search);
        params.set('date', this.formatDate(date));
        window.history.pushState({}, '', `${window.location.pathname}?${params.toString()}`);
    }

    getBaseUri() {
        return `http://telemedi-zadanie.localhost/api/exchange-rates`;
    }

    componentDidMount() {
        this.fetchRatesForDate(this.state.date);
    }

    fetchRatesForDate(selectedDate) {
        console.log('fetchRatesForDate', selectedDate);

        this.setState({ loading: true, error: null, rates: null });
        const url = this.getBaseUri() + `?date=${selectedDate}`;
        fetch(url).then((response) => {
                if (!response.ok) {
                    this.setState({ error: true });
                    this.setState({ notFound: response.status === 404 });
                    throw new Error('Server error: ' + response.status);
                }
                return response.json();
            }).then((data) => {
                console.log('Data fetched: ', data);
                this.setState({
                    date: data.date,
                    rates: data.rates,
                    error: data.error,
                    notFound: data.notFound
                });
            }).catch((error) => {
                console.error(error);
                this.setState({
                    rates: null,
                    error: true
                });
            }).finally(() => {
                this.setState({ loading: false });
        });
    }

    handleDateChange(event) {
        const selectedDate = event.target.value;
        const date = new Date(selectedDate);
        const isWeekend = date.getDay() === 0 || date.getDay() === 6;
        this.updateDateParam(date);
        this.setState({ date: selectedDate, showWeekendInfo: isWeekend });

        if (!isWeekend) {
            this.fetchRatesForDate(selectedDate);
        } else {
            this.setState({ rates: null, error: null, notFound: null });
            console.log("The service doesnt offer data on weekends :)")
        }
    }

    isValidDate(dateStr) {
        let date = new Date(dateStr);
        return date instanceof Date && !isNaN(date.getTime())
            && date.getUTCFullYear() > 2022
            && date.getUTCFullYear() < 2025;
    }

    formatDate(date) {
        console.log('formatDate', date);
        const y = date.getUTCFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed, so we add 1
        const d = String(date.getDate()).padStart(2, '0'); // Pads single digits with 0

        return `${y}-${m}-${d}`;
    }

    render() {
        return (
            <div>
                <section className="row-section">
                    <div className="container">
                        <div className="row mt-4">
                            <div className="col-md-8 offset-md-2">
                                <h3 className="text-center"><span>Currency rates</span></h3>
                                <div className="form-group">
                                    <label htmlFor="date-picker">
                                        Select date
                                    </label>
                                    <input
                                        title="The service doesnt offer data on weekend days!"
                                        className="form-control"
                                        id="date-picker"
                                        lang="pl-PL"
                                        type="date"
                                        value={this.state.date}
                                        onChange={this.handleDateChange}
                                        min="2023-01-01"
                                        max={new Date().toISOString().split('T')[0]} // Set the maximum date to today
                                    />
                                </div>
                                {this.state.loading && !this.state.error && (
                                    <div className="text-center">
                                        <span className="fa fa-spin fa-spinner fa-4x"></span>
                                    </div>
                                )}
                                {!this.state.error && this.state.showWeekendInfo && (
                                    <div className="alert alert-info">
                                        {this.state.date} is a weekend, automatic loading turned off.
                                    </div>
                                )}
                                {this.state.error && this.state.notFound && (
                                    <div className="alert alert-info">
                                        NotFound: external server responded with 404
                                    </div>
                                )}
                                {this.state.error && !this.state.notFound && (
                                    <div className="alert alert-warning">
                                        {this.state.error}
                                    </div>
                                )}
                                {!this.state.error && this.state.rates && (
                                    <div className="text-center">
                                        <div className="alert alert-success">
                                            Showing currency rates for date: <span className="text-monospace">{this.state.date}</span>
                                        </div>
                                        <div className="table-responsive">
                                            <table className="table table-striped table-bordered table-hover table-sm">
                                                <thead className="thead-light">
                                                <tr>
                                                    <th scope="col">Currency</th>
                                                    <th scope="col">Code</th>
                                                    <th scope="col">Ask price</th>
                                                    <th scope="col">Bid price</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                {this.state.rates.map((item) => (
                                                    <tr key={item.code}>
                                                        <td>{item.name}</td>
                                                        <td>{item.code}</td>
                                                        <td>{item.ask}</td>
                                                        <td>{item.bid}</td>
                                                    </tr>
                                                ))}
                                                </tbody>
                                            </table>
                                            <div className="text-small text-right text-light">All values are presented in PLN</div>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        );
    }
}

export default CurrencyRates;
