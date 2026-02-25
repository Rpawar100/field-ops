# Field Operations Mobile App - React Native

## Project Overview

This is a React Native mobile application for the Field Operations Management System. It provides field staff (FAs, TSLs, RMs) with a comprehensive mobile experience for managing activities, attendance, demos, and more.

## Features

### Core Features (MVP)
- **Authentication**: Mobile number + password login with JWT tokens
- **Attendance Tracking**: Check-in/check-out with GPS location and selfie capture
- **Activity Management**: View and execute daily activities with photo capture
- **Tour Planning**: Create and manage Advance Tour Plans (ATP) with beat assignments
- **Demo Tracking**: Execute and track demo activities at different growth stages
- **Offline Capability**: Queue operations and sync when internet is available
- **Notifications**: Push notifications for pending activities and approvals

### Advanced Features
- GPS real-time location tracking
- Photo capture with metadata
- SQLite local storage for offline operations
- Automatic sync manager
- QR code scanning for farmer/retailer onboarding
- Voice notes for activities
- Batch operations for multiple entries

## Project Structure

```
mobile/
├── App.js                             # Main app entry point
├── app.json                           # React Native config
├── package.json                       # Dependencies
├── src/
│   ├── navigation/                    # React Navigation setup
│   │   ├── RootNavigator.js
│   │   ├── AuthNavigator.js          # Login/Register screens
│   │   ├── AppNavigator.js           # Main app screens
│   │   ├── AttendanceNavigator.js
│   │   ├── ActivityNavigator.js
│   │   └── SettingsNavigator.js
│   ├── screens/
│   │   ├── Auth/
│   │   │   ├── LoginScreen.js
│   │   │   ├── RegisterScreen.js
│   │   │   └── ForgotPasswordScreen.js
│   │   ├── Home/
│   │   │   ├── DashboardScreen.js
│   │   │   └── MenuScreen.js
│   │   ├── Attendance/
│   │   │   ├── CheckInScreen.js      # GPS + Selfie
│   │   │   ├── CheckOutScreen.js
│   │   │   ├── AttendanceHistoryScreen.js
│   │   │   └── AttendanceReportScreen.js
│   │   ├── Activities/
│   │   │   ├── ActivityListScreen.js
│   │   │   ├── ActivityDetailScreen.js
│   │   │   ├── ExecuteActivityScreen.js
│   │   │   └── PhotoCaptureScreen.js
│   │   ├── ATP/
│   │   │   ├── CreateATPScreen.js
│   │   │   ├── ATPDetailScreen.js
│   │   │   └── BeatSelectionScreen.js
│   │   ├── Demo/
│   │   │   ├── DemoListScreen.js
│   │   │   ├── DemoExecutionScreen.js
│   │   │   └── DemoRescheduleScreen.js
│   │   ├── Farmer/
│   │   │   ├── FarmerListScreen.js
│   │   │   ├── FarmerRegistrationScreen.js
│   │   │   └── FarmerDetailScreen.js
│   │   └── Settings/
│   │       ├── ProfileScreen.js
│   │       ├── SyncScreen.js
│   │       └── SettingsScreen.js
│   ├── components/
│   │   ├── FormComponents/
│   │   │   ├── TextInputField.js
│   │   │   ├── DatePickerField.js
│   │   │   ├── SelectField.js
│   │   │   └── PhotoPickerField.js
│   │   ├── ListComponents/
│   │   │   ├── FlatListWithRefresh.js
│   │   │   ├── ActivityCard.js
│   │   │   └── FarmerCard.js
│   │   ├── CommonComponents/
│   │   │   ├── Header.js
│   │   │   ├── FloatingButton.js
│   │   │   ├── LoadingOverlay.js
│   │   │   ├── ErrorBoundary.js
│   │   │   └── NoDataMessage.js
│   │   └── MapComponents/
│   │       ├── LocationMap.js
│   │       └── BeatMap.js
│   ├── services/
│   │   ├── api.js                   # Axios instance with interceptors
│   │   ├── authService.js           # Authentication logic
│   │   ├── attendanceService.js     # Attendance API calls
│   │   ├── activityService.js       # Activity API calls
│   │   ├── demoService.js           # Demo API calls
│   │   ├── farmerService.js         # Farmer API calls
│   │   ├── locationService.js       # GPS location services
│   │   ├── cameraService.js         # Camera and photo services
│   │   ├── offlineStorageService.js # SQLite operations
│   │   └── syncService.js           # Offline sync manager
│   ├── hooks/
│   │   ├── useAuth.js               # Auth state hook
│   │   ├── useLocation.js           # GPS tracking hook
│   │   ├── useCamera.js             # Camera operations hook
│   │   ├── useSyncManager.js        # Offline sync hook
│   │   ├── useNetworkState.js       # Network connection hook
│   │   └── useForm.js               # Form management hook
│   ├── store/
│   │   ├── StoreProvider.js         # Redux store provider
│   │   ├── store.js                 # Redux configuration
│   │   ├── authSlice.js             # Auth state management
│   │   ├── userSlice.js             # User state
│   │   ├── activitiesSlice.js       # Activities state
│   │   ├── attendanceSlice.js       # Attendance state
│   │   ├── offlineSlice.js          # Offline queue state
│   │   └── uiSlice.js               # UI state (loading, errors)
│   ├── database/
│   │   ├── sqlite.js                # SQLite initialization
│   │   ├── migrations/
│   │   │   ├── createTables.js
│   │   │   └── schema.js
│   │   └── queries/
│   │       ├── attendanceQueries.js
│   │       ├── activityQueries.js
│   │       └── syncQueries.js
│   ├── utils/
│   │   ├── validation.js
│   │   ├── dateUtils.js
│   │   ├── formatUtils.js
│   │   ├── gpsUtils.js
│   │   ├── imageUtils.js
│   │   ├── storageUtils.js
│   │   ├── errorHandler.js
│   │   └── permissions.js            # App permissions handling
│   ├── styles/
│   │   ├── colors.js                # Color constants
│   │   ├── spacing.js               # Spacing constants
│   │   ├── typography.js            # Font styles
│   │   └── commonStyles.js          # Common stylesheet
│   └── config/
│       ├── constants.js             # App constants
│       ├── permissions.js           # Required permissions
│       └── apiConfig.js             # API configuration
├── android/                          # Android native code
├── ios/                              # iOS native code
├── __tests__/                        # Jest test files
└── README.md
```

## Installation & Setup

### Prerequisites
- Node.js 14+ and npm
- React Native CLI
- Android SDK (for Android development)
- Xcode (for iOS development)
- Git

### Setup Steps

```bash
# 1. Navigate to mobile directory
cd mobile

# 2. Install dependencies
npm install

# 3. Install CocoaPods (iOS only)
cd ios && pod install && cd ..

# 4. Update API URL in src/services/api.js
# Change API_URL to your server IP/domain

# 5. Run on Android
npm run android

# 6. Run on iOS
npm run ios
```

## Key Services

### Authentication Service (`authService.js`)
- User login with JWT tokens
- Logout and token refresh
- Password reset functionality

### Attendance Service (`attendanceService.js`)
- Check-in with GPS coordinates
- Selfie capture
- Check-out tracking
- Attendance history

### Activity Service (`activityService.js`)
- Fetch daily activities
- Execute activities with attributes
- Photo capture and upload

### Offline Sync Service (`syncService.js`)
- Queue operations when offline
- Automatic sync when connectivity restored
- Conflict resolution

## Redux State Structure

```javascript
{
  auth: {
    isAuthenticated: boolean,
    user: User,
    token: string,
    loading: boolean,
    error: string
  },
  activities: {
    items: Activity[],
    selectedActivity: Activity,
    loading: boolean,
    error: string
  },
  attendance: {
    today: Attendance,
    history: Attendance[],
    loading: boolean,
    error: string
  },
  offline: {
    queue: OfflineAction[],
    isSyncing: boolean,
    lastSyncTime: Date
  },
  ui: {
    loading: boolean,
    error: string,
    notification: Notification
  }
}
```

## Permissions Required

### Android
- `CAMERA` - For selfie capture
- `ACCESS_FINE_LOCATION` - For GPS tracking
- `ACCESS_COARSE_LOCATION` - For coarse location
- `WRITE_EXTERNAL_STORAGE` - For photo storage
- `READ_EXTERNAL_STORAGE` - For reading photos
- `INTERNET` - For API calls

### iOS
- Camera
- Location (Always and When in Use)
- Photos/Videos

## Offline Functionality

The app supports full offline operation:

1. **Queue Management**: Operations are queued when offline
2. **Local Storage**: SQLite stores user operations
3. **Automatic Sync**: Syncs when internet is restored
4. **Conflict Resolution**: Server version wins in conflicts

### Offline Queue Structure
```javascript
{
  id: string,
  action: 'CREATE_ACTIVITY' | 'CHECK_IN' | etc,
  entityType: string,
  entityId: string,
  payload: any,
  timestamp: Date,
  synced: boolean,
  retries: number
}
```

## API Integration

All API calls go through the `api.js` Axios instance which:
- Automatically includes JWT token in headers
- Handles 401 Unauthorized responses
- Has 10-second timeout
- Logs all requests/responses in debug mode

## Development

### Running Tests
```bash
npm test
```

### Debugging
```bash
# Start React Native metro bundler
npm start

# Run debugger
npm run android -- --dev
```

### Building for Production

**Android:**
```bash
cd android
./gradlew assembleRelease
```

**iOS:**
```bash
cd ios
xcodebuild -workspace FieldOperations.xcworkspace -scheme FieldOperations -configuration Release
```

## Key Features Implementation

### Attendance Check-In with GPS + Selfie
```javascript
// src/screens/Attendance/CheckInScreen.js
- Capture selfie
- Get GPS coordinates
- Submit with timestamp
```

### Activity Execution with Photos
```javascript
// src/screens/Activities/ExecuteActivityScreen.js
- Select farmers/retailers
- Capture photos (up to 5)
- Fill activity attributes
- Submit with offline queue support
```

### Demo Stage Tracking
```javascript
// src/screens/Demo/DemoExecutionScreen.js
- View demo stages timeline
- Capture growth stage photos
- Record attributes
-Reschedule if necessary
```

### Offline Sync
```javascript
// src/services/syncService.js
- Watches network connection
- Auto-syncs when online
- Handles conflicts
- Shows sync status
```

## Troubleshooting

### Common Issues

1. **API connection fails**
   - Verify API_URL in `src/services/api.js`
   - Check if backend server is running
   - Verify phone/emulator can reach server

2. **Camera permission denied**
   - Grant permissions in app settings
   - Restart app

3. **Location not captured**
   - Enable location services on device
   - Grant location permissions
   - Check GPS signal

4. **Photos not uploading**
   - Check internet connection
   - Verify storage permissions
   - Check photo file size (max 5MB recommended)

## Environment Variables

Create `.env` file in mobile directory (optional):
```
API_BASE_URL=http://192.168.1.100:8000
API_VERSION=v1
DEBUG=true
SYNC_INTERVAL=300000
```

## Dependencies Overview

- **react-navigation**: Navigation between screens
- **redux + react-redux**: State management
- **axios**: HTTP client
- **react-native-camera**: Camera access
- **react-native-geolocation**: GPS access
- **react-native-sqlite-storage**: Offline database
- **moment**: Date/time manipulation
- **@react-native-async-storage**: Local storage

## Performance Optimization

- ListView pagination (20 items per page)
- Image compression before upload
- Automatic cache clearing (30 days)
- Lazy loading of screens
- Redux selectors for optimized renders

## Security

- JWT token stored in secure AsyncStorage
- HTTPS enforced for API calls
- Local encryption for sensitive data
- Password hashing (server-side)
- SSL certificate pinning (recommended)

## Support

For issues or feature requests, contact the development team or refer to the implementation plan.

---

**Status**: Initial structure complete, ready for screen implementation.
