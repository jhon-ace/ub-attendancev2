import React from 'react';
import { createRoot } from 'react-dom/client';
import StudentAttendancePortal from './StudentAttendancePortal';

document.addEventListener('DOMContentLoaded', () => {
    const rootElement = document.getElementById('react-root');
    if (rootElement) {
        const curdateDataIn = JSON.parse(rootElement.dataset.curdateDataIn);
        const curdateDataOut = JSON.parse(rootElement.dataset.curdateDataOut);
        const error = rootElement.dataset.error || null;
        const success = rootElement.dataset.success || null;

        const root = createRoot(rootElement);
        root.render(
            <StudentAttendancePortal
                curdateDataIn={curdateDataIn}
                curdateDataOut={curdateDataOut}
                error={error}
                success={success}
            />
        );
    }
});