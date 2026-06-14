import React, { useState, useEffect } from 'react';
import axios from 'axios';

interface ServiceAreaManagerProps {
  initialAreas: string[];
  onSave?: (areas: string[]) => void;
}

export const ServiceAreaManager: React.FC<ServiceAreaManagerProps> = ({
  initialAreas = [],
  onSave,
}) => {
  const [areas, setAreas] = useState<string[]>(initialAreas);
  const [newArea, setNewArea] = useState('');
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState('');

  const suggestedAreas = [
    'Nairobi Central',
    'Eastlands',
    'Westlands',
    'South B',
    'South C',
    'Upper Hill',
    'Kilimani',
    'Lavington',
    'Karen',
    'Mombasa',
    'Kisumu',
    'Nakuru',
    'Eldoret',
    'Thika',
    'Kikuyu',
  ];

  const handleAddArea = (area: string) => {
    if (!area.trim()) return;
    if (areas.includes(area)) {
      setMessage('Area already added');
      return;
    }
    setAreas([...areas, area]);
    setNewArea('');
    setMessage('');
  };

  const handleRemoveArea = (area: string) => {
    setAreas(areas.filter((a) => a !== area));
  };

  const handleSave = async () => {
    if (areas.length === 0) {
      setMessage('Add at least one service area');
      return;
    }

    setLoading(true);
    try {
      const response = await axios.put('/api/professional/service-areas', {
        service_areas: areas,
      });

      setMessage('Service areas saved successfully!');
      onSave?.(areas);

      setTimeout(() => setMessage(''), 3000);
    } catch (error: any) {
      setMessage(error.response?.data?.error || 'Failed to save service areas');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="bg-white rounded-lg p-6 shadow">
      <h3 className="text-lg font-semibold mb-4">Service Areas</h3>
      <p className="text-gray-600 text-sm mb-4">
        Select the areas you provide services in. You'll choose one when going online.
      </p>

      {/* Quick select buttons */}
      <div className="mb-6">
        <label className="block text-sm font-medium text-gray-700 mb-3">Quick add</label>
        <div className="grid grid-cols-2 sm:grid-cols-3 gap-2">
          {suggestedAreas.map((area) => (
            <button
              key={area}
              onClick={() => handleAddArea(area)}
              disabled={areas.includes(area)}
              className="text-sm px-3 py-2 border rounded hover:bg-blue-50 disabled:opacity-50 disabled:bg-gray-100"
            >
              {area}
            </button>
          ))}
        </div>
      </div>

      {/* Custom area input */}
      <div className="mb-6">
        <label className="block text-sm font-medium text-gray-700 mb-2">Add custom area</label>
        <div className="flex gap-2">
          <input
            type="text"
            value={newArea}
            onChange={(e) => setNewArea(e.target.value)}
            onKeyPress={(e) => {
              if (e.key === 'Enter') handleAddArea(newArea);
            }}
            placeholder="e.g., Westlands, Kilimani"
            className="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <button
            onClick={() => handleAddArea(newArea)}
            className="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
          >
            Add
          </button>
        </div>
      </div>

      {/* Selected areas */}
      <div className="mb-6">
        <label className="block text-sm font-medium text-gray-700 mb-3">Your service areas</label>
        {areas.length === 0 ? (
          <p className="text-gray-500 text-sm">No service areas added yet</p>
        ) : (
          <div className="flex flex-wrap gap-2">
            {areas.map((area) => (
              <div
                key={area}
                className="bg-blue-100 text-blue-800 px-3 py-2 rounded-full flex items-center gap-2"
              >
                <span>{area}</span>
                <button
                  onClick={() => handleRemoveArea(area)}
                  className="text-blue-600 hover:text-blue-800 font-bold"
                >
                  ×
                </button>
              </div>
            ))}
          </div>
        )}
      </div>

      {message && (
        <div
          className={`p-3 rounded mb-4 text-sm ${
            message.includes('successfully')
              ? 'bg-green-100 text-green-800'
              : 'bg-red-100 text-red-800'
          }`}
        >
          {message}
        </div>
      )}

      <button
        onClick={handleSave}
        disabled={loading || areas.length === 0}
        className="w-full px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 disabled:opacity-50"
      >
        {loading ? 'Saving...' : 'Save Service Areas'}
      </button>
    </div>
  );
};
