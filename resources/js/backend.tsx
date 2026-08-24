import React from 'react';
import { createRoot } from 'react-dom/client';

/**
 * Backend Admin Portal Entry Point
 * 
 * This file is executed strictly for the Admin / Backend views.
 * It remains separate from the React Frontend SPA app (`app.tsx`).
 */

console.log('Backend Admin Portal scripts initialized');

const container = document.getElementById('backend-root');

if (container) {
    const root = createRoot(container);
    root.render(
        <React.StrictMode>
            {/* Backend React Root Component / Features if mounted */}
        </React.StrictMode>
    );
}
