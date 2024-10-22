import React, {useEffect, useState} from 'react';
import {useParams, useHistory} from 'react-router-dom';
import DateSelector from './DateSelector';
import RatesTable from './RatesTable';
import Loading from './Loading';
import ErrorMessage from './ErrorMessage';
import {fetchTodayRates, fetchHistoricalRates} from '../../services/axios/api';
import '../../css/CurrencyRates.css';

const CurrencyRates = () => {
    const {date} = useParams();
    const [rates, setRates] = useState({});
    const [todayRates, setTodayRates] = useState({});
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [selectedDate, setSelectedDate] = useState(date || new Date().toISOString().split('T')[0]);
    const history = useHistory();

    const today = new Date().toISOString().split('T')[0];

    useEffect(() => {
        const fetchRates = async() => {
            setLoading(true);
            setError(null);

            try {
                if (selectedDate === today) {
                    const todayData = await fetchTodayRates();
                    setTodayRates(todayData);
                    setRates({});
                } else {
                    const historicalData = await fetchHistoricalRates(selectedDate);
                    setRates(historicalData);

                    const todayData = await fetchTodayRates();
                    setTodayRates(todayData);
                }
            } catch (error) {
                console.error('Error sprawdzanie walut:', error);
                setError(error.message);
            } finally {
                setLoading(false);
            }
        };

        fetchRates();
    }, [selectedDate, today]);

    const handleDateChange = (newDate) => {
        setSelectedDate(newDate);
        history.push(`/exchange-rates/${newDate}`);
    };

    return (
        <div className="currency-rates-container">
            <h1>Kurs Walut dnia {selectedDate}</h1>
            <DateSelector selectedDate={selectedDate} onDateChange={handleDateChange}/>
            {error && <ErrorMessage message={error}/>}
            {loading ? (
                <Loading/>
            ) : (
                <>
                    {selectedDate !== today && (
                        <>
                            <h2>Historical Rates (for {selectedDate})</h2>
                            <RatesTable rates={rates} todayRates={todayRates}/>
                        </>
                    )}
                    <h2>{selectedDate === today ? 'Dzisiejszy Kurs' : 'Dzisieszy Kurs Porównanie  '}</h2>
                    <RatesTable rates={todayRates}/>
                </>
            )}
        </div>
    );
};

export default CurrencyRates;
