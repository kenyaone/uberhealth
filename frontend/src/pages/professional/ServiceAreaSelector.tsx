import React, { useState } from 'react';
import axios from 'axios';

interface ServiceAreaSelectorProps {
  serviceAreas: string[];
  onSelect: (area: string) => void;
  onCancel: () => void;
  isLoading?: boolean;
}

export const ServiceAreaSelector: React.FC<ServiceAreaSelectorProps> = ({
  serviceAreas,
  onSelect,
  onCancel,
  isLoading = false,
}) => {
  const [selected, setSelected] = useState<string>('');

  const handleSubmit = () => {
    if (!selected) {
      alert('Please select a service area');
      return;
    }
    onSelect(selected);
  };

  if (!serviceAreas || serviceAreas.length === 0) {
    return (
      <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div className="bg-white rounded-lg p-6 max-w-md w-full mx-4">
          <h3 className="text-lg font-semibold mb-4">Service Areas</h3>
          <p className="text-gray-600 mb-6">
            You haven't set up any service areas yet. Please add service areas in your settings first.
          </p>
          <button
            onClick={onCancel}
            className="w-full bg-gray-300 text-gray-800 py-2 rounded hover:bg-gray-400"
          >
            Cancel
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div className="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 className="text-lg font-semibold mb-4">Select Service Area</h3>
        <p className="text-gray-600 mb-4">Where are you available today?</p>

        <div className="space-y-2 mb-6">
          {serviceAreas.map((area) => (
            <label key={area} className="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50">
              <input
                type="radio"
                name="service_area"
                value={area}
                checked={selected === area}
                onChange={(e) => setSelected(e.target.value)}
                className="mr-3"
              />
              <span className="text-gray-700">{area}</span>
            </label>
          ))}
        </div>

        <div className="flex gap-3">
          <button
            onClick={onCancel}
            disabled={isLoading}
            className="flex-1 bg-gray-300 text-gray-800 py-2 rounded hover:bg-gray-400 disabled:opacity-50"
          >
            Cancel
          </button>
          <button
            onClick={handleSubmit}
            disabled={isLoading || !selected}
            className="flex-1 bg-blue-500 text-white py-2 rounded hover:bg-blue-600 disabled:opacity-50"
          >
            {isLoading ? 'Setting...' : 'Go Online'}
          </button>
        </div>
      </div>
    </div>
  );
};
